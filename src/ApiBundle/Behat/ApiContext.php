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
use OAuthBundle\Entity\AccessToken;
use OAuthBundle\Entity\Client;
use OAuthBundle\Entity\RefreshToken;
use OAuthBundle\Entity\User;
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
            if (isset($row['FacebookID'])) {
                $user->setFacebookId($row['FacebookID']);
            }
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
        $factory = new SimpleFactory();
        $matcher = $factory->createMatcher();
        $match = $matcher->match($this->response->getBody()->getContents(), $string->getRaw());
        \PHPUnit_Framework_Assert::assertTrue($match, $matcher->getError());
    }
}
