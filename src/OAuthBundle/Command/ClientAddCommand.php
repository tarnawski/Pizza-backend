<?php

namespace OAuthBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;

class ClientAddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('allowed_redirect_uris', '', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL),
                new InputOption(
                    'allowed_grant_types',
                    '',
                    InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                    '',
                    array('authorization_code', 'token')
                )
            ))
            ->setName('oauth-server:client:create')
            ->setDescription('Creates new client with access to OAuth 2.0 API endpoints');
    }

    protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris($input->getOption('allowed_redirect_uris'));
        $client->setAllowedGrantTypes($input->getOption('allowed_grant_types'));
        $clientManager->updateClient($client);
        $output->writeln('Client successfully created');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $redirectURIs = array();
        while ($redirectURI = $dialog->ask(
            $output,
            $dialog->getQuestion('Allowed redirect URIs', $input->getOption('allowed_redirect_uris'))
        )) {
            $redirectURIs[] = $redirectURI;
        }
        $input->setOption('allowed_redirect_uris', $redirectURIs);
    }
}
