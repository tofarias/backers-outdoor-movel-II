<?php
declare(strict_types=1);
namespace Backers\Plugins;

use Backers\Repository\ClientRepository;
use Backers\Repository\RepositoryFactory;
use Backers\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use Backers\Models\{Client,User};

class DbPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $capsule = new Capsule();
        $config = include __DIR__.'/../../config/db.php';
        $capsule->addConnection($config['development']);
        $capsule->bootEloquent();

        $container->add('repository.factory', new RepositoryFactory());

        $container->addLazy(
            'client.repository', function (ContainerInterface $container) {
                return $container->get('repository.factory')->factory(Client::class);
            }
        );

        $container->addLazy(
            'user.repository', function (ContainerInterface $container) {
                return $container->get('repository.factory')->factory(User::class);
            }
        );
    }
}