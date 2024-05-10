<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $conversation = new Conversation();

            $conversation->setTitle('Conversation ' . $i);
            $conversation->setCreatedBy($this->getReference('user-' . $i));
            $conversation->addParticipant($this->getReference('user-' . $i));
            $conversation->addParticipant($this->getReference('user-' . ($i + 5)));
            $manager->persist($conversation);
            $this->addReference('conversation-' . $i, $conversation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
