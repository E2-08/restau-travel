<?php
namespace App\tests\user;

use App\Entity\User;
// use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use PHPUnit\Framework\TestCase;




class UserTest extends TestCase
{

    public function test1AddUser()
    {
        $user = new User();
        $user->setLastName('Eudes')
            ->setFirstName('KOBEMBA')
            ->setHash('password')
            ->setEmail('eudes@ici08.fr')
            ->setAvatar('');
        $this->assertEquals($user->getEmail(), 'eudes@ici08.fr');
        $this->assertEquals($user->getLastName(), 'Eudes');
        $this->assertEquals($user->getFirstName(), 'KOBEMBA');
        $this->assertEquals($user->getHash(), 'password');
        $this->assertEquals($user->getAvatar(), '');

        return;
    }

    public function test2AddUser()
    {
        $user = new User();
        $user->setLastName('')
            ->setFirstName('')
            ->setHash('')
            ->setEmail('')
            ->setAvatar('');

        $this->assertEquals($user->getEmail(), '');
        $this->assertEquals($user->getLastName(), '');
        $this->assertEquals($user->getFirstName(), '');
        $this->assertEquals($user->getHash(), '');
        $this->assertEquals($user->getAvatar(), '');

        static::assertInstanceOf(User::class, $user);

        return;
    }

}