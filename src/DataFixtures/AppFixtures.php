<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugger = new AsciiSlugger();

        // On créer 10 catégories
        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setName($faker->words(mt_rand(1, 2), true));
            $manager->persist($category);

            // On créer pour chaque catégorie entre 20 et 30 Articles
            for ($j = 1; $j <= mt_rand(10, 20); $j++) {
                $content = $faker->paragraphs(5, true) . "\n\n";

                $article = new Article();
                $article
                    ->setTitle($faker->sentence)
                    ->setSlug($slugger->slug($article->getTitle()))
                    ->setSummary($faker->paragraphs(mt_rand(2, 5), true))
                    ->setContent($content)
                    ->setCategories($category)
                    ->setPublishedAt($faker->dateTimeBetween('-6 months'));
                $manager->persist($article);

                // On créer pour chaque article entre 4 à 10 commentaire
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $published_at = $faker->dateTimeBetween('-' . (new \DateTime())->diff($article->getPublishedAt())->days . ' days');
                    $content = $faker->paragraphs(2, true). "\n\n";

                    $comment = new Comment();
                    $comment
                        ->setName($faker->name())
                        ->setArticle($article)
                        ->setMessage($content)
                        ->setPublishedAt($published_at);
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
