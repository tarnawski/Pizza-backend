<?php

namespace ApiBundle\Behat;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\WebApiExtension\Context\WebApiContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Coduo\PHPMatcher\Matcher;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FOS\OAuthServerBundle\Model\TokenInterface;
use OAuthBundle\Entity\AccessToken;
use OAuthBundle\Entity\Client;
use OAuthBundle\Entity\RefreshToken;
use OAuthBundle\Entity\User;
use PizzaBundle\Entity\Customer;
use PizzaBundle\Entity\Price;
use PizzaBundle\Entity\Product;
use PizzaBundle\Entity\PromoCode;
use PizzaBundle\Entity\Type;
use SebastianBergmann\Diff\Differ;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Defines application features from the specific context.
 */
class ApiContext extends WebApiContext implements Context, SnippetAcceptingContext, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @BeforeScenario @cleanDB
     * @AfterScenario @cleanDB
     */
    public function cleanDB()
    {
        $application = new Application($this->getKernel());
        $application->setAutoExit(false);
        $application->run(new StringInput("doctrine:schema:drop --force -n -q"));
        $application->run(new StringInput("doctrine:schema:create -n -q"));
    }

    private function createToken(TokenInterface $token, $row)
    {
        $reflectionClass = new \ReflectionClass(get_class($token));
        $id = $reflectionClass->getProperty('id');
        $id->setAccessible(true);
        $id->setValue($token, $row['ID']);
        $token->setToken($row['Token']);
        $expiresAt = new \DateTime($row['Expires at']);
        $token->setExpiresAt($expiresAt->getTimestamp());
        /** @var Client $client */
        $client = $this->getManager()->getReference(Client::class, $row['Client']);
        $token->setClient($client);
        /** @var User $user */
        $user = $this->getManager()->getReference(User::class, $row['User']);
        $token->setUser($user);
        $this->getManager()->persist($token);
        $metadata = $this->getManager()->getClassMetaData(get_class($token));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_CUSTOM);
    }
    
    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @Given There are the following clients:
     */
    public function thereAreTheFollowingClients(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $client = new Client();
            $reflectionClass = new \ReflectionClass(Client::class);
            $id = $reflectionClass->getProperty('id');
            $id->setAccessible(true);
            $id->setValue($client, $row['ID']);
            $client->setRandomId($row['Random ID']);
            $client->setSecret($row['Secret']);
            $client->setRedirectUris(explode(',', $row['URIs']));
            $client->setAllowedGrantTypes(explode(',', $row['Grant Types']));

            $this->getManager()->persist($client);
        }

        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given there are the following users:
     */
    public function thereAreTheFollowingUsers(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $user = new User();
            $user->setUsername($row['Username']);
            $user->setEmail($row['Email']);
            $user->setPlainPassword($row['Password']);
            $user->setSuperAdmin(boolval($row['Superadmin']));
            $user->setEnabled($row['Enabled']);
            $user->setRoles(explode(',', $row['Role']));
            $this->getManager()->persist($user);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following refresh tokens:
     */
    public function thereAreTheFollowingRefreshTokens(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $accessToken = new RefreshToken();
            $this->createToken($accessToken, $row);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following access tokens:
     */
    public function thereAreTheFollowingAccessTokens(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $accessToken = new AccessToken();
            $this->createToken($accessToken, $row);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Then the JSON response should match:
     */
    public function theJsonResponseShouldMatch(PyStringNode $string)
    {
//        var_dump($this->response->getBody()->getContents());exit;
        $factory = new SimpleFactory();
        $matcher = $factory->createMatcher();
        $match = $matcher->match($this->response->getBody()->getContents(), $string->getRaw());
        \PHPUnit_Framework_Assert::assertTrue($match, $matcher->getError());
    }

    /**
     * @Given There are the following applications:
     */
    public function thereAreTheFollowingApplications(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $application = new \PizzaBundle\Entity\Application();
            $application->setName($row['Name']);
            $application->setDescription($row['Description']);
            $application->setHomepage($row['Homepage']);
            $application->setDemo($row['Demo']);
            /** @var User $user */
            $user = $this->getManager()->getRepository(User::class)->find($row['UserID']);
            $application->addUser($user);
            $application->setCreateDate(new \DateTime());
            $this->getManager()->persist($application);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following promo codes:
     */
    public function thereAreTheFollowingPromoCodes(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $promocode = new PromoCode();
            $promocode->setName($row['Name']);
            $promocode->setPercent($row['Percent']);
            $promocode->setOverall($row['Overall']);
            $promocode->setValue($row['Value']);
            $promocode->setCode($row['Code']);
            $promocode->setAvailable($row['Available']);
            /** @var \PizzaBundle\Entity\Application $application */
            $application = $this->getManager()->getRepository(\PizzaBundle\Entity\Application::class)->find($row['ApplicationID']);
            $promocode->setApplication($application);
            $this->getManager()->persist($promocode);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following customers:
     */
    public function thereAreTheFollowingCustomers(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $customer = new Customer();
            $customer->setFirstName($row['FirstName']);
            $customer->setLastName($row['LastName']);
            $customer->setEmail($row['Email']);
            $customer->setPhone($row['Phone']);
            $customer->setAddress($row['Address']);
            /** @var \PizzaBundle\Entity\Application $application */
            $application = $this->getManager()->getRepository(\PizzaBundle\Entity\Application::class)->find($row['ApplicationID']);
            $customer->setApplication($application);
            $this->getManager()->persist($customer);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following types:
     */
    public function thereAreTheFollowingTypes(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $type = new Type();
            $type->setName($row['Name']);
            $this->getManager()->persist($type);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following prices:
     */
    public function thereAreTheFollowingPrices(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $price = new Price();
            $price->setType($row['Type']);
            $price->setValue($row['Value']);
            $this->getManager()->persist($price);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @Given There are the following products:
     */
    public function thereAreTheFollowingProducts(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $product = new Product();
            $product->setName($row['Name']);
            $product->setDescription($row['Description']);
            $product->setAvailable($row['Available']);
            /** @var \PizzaBundle\Entity\Application $application */
            $application = $this->getManager()->getRepository(\PizzaBundle\Entity\Application::class)->find($row['ApplicationID']);
            $product->setApplication($application);
            /** @var Type $type */
            $type = $this->getManager()->getRepository(Type::class)->find($row['TypeID']);
            $product->setType($type);
            $prices = explode(',', $row['PriceID']);
            foreach ($prices as $item){
                /** @var Price $price */
                $price = $this->getManager()->getRepository(Price::class)->find($item);
                $product->addPrice($price);
            }
            $this->getManager()->persist($product);

        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }
}
