<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Page;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Users.
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create(
                sprintf("email%d@email.com", $i),
                sprintf("name%d", $i)
            );
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);

            $users[] = $user;
        }

        // Pages.
        for ($j = 1; $j <= 2; $j++) {
            $page = Page::create(sprintf("Page%d", $j), "Content", $users[array_rand($users)]);
            $manager->persist($page);

            // Comments.
            for ($k = 1; $k <= 10; $k++) {
                $comment = Comment::create(sprintf("Comment %d", $k), $users[array_rand($users)], $page);
                shuffle($users);

                // Replies.
                $x = 1;
                foreach (array_slice($users, 0, 5) as $user) {
                    $reply = Comment::create(sprintf("Reply %d", $x), $user, $page);

                    $reply->setParent($comment);
                    $comment->addReply($reply);
                    $x++;
                }
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
