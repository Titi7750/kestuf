<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $message = new Message();

            $message->setContent('Message ' . $i);
            $message->setSendAt(new \DateTimeImmutable());
            $message->setIsRead(false);
            $message->setSendBy($this->getReference('user-' . $i));
            $message->setConversation($this->getReference('conversation-' . $i));
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ConversationFixtures::class,
        ];
    }
}
