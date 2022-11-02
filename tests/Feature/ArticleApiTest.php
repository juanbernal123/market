<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cat_get_all_articles()
    {
        $article = Article::factory(10)->create();

        $this->getJson(route('articles.index'))
            ->assertJsonFragment([
                'name'        => $article[2]->name,
                'description' => $article[2]->description,
            ]);
    }

    /** @test */
    public function can_get_one_article()
    {
        $article = Article::factory()->create();

        $this->getJson(route('articles.show', $article))
            ->assertJsonFragment([
                'name'        => $article->name,
                'description' => $article->description,
            ]);
    }

    /** @test */
    public function cat_create_articles()
    {
        // $this->postJson(route('articles.store'), [])->assertJsonValidationErrorFor('name');
        $this->postJson(route('articles.store'), [])->assertJsonValidationErrors(['name', 'description']);

        $this->postJson(route('articles.store'), [
            'name'        => 'Articulo desde test',
            'description' => 'descripcion desde test',
        ])
            ->assertJsonFragment([
                'name'        => 'Articulo desde test',
                'description' => 'descripcion desde test',
            ]);

        // verificamos en la BD
        $this->assertDatabaseHas('articles', [
            'name'        => 'Articulo desde test',
            'description' => 'descripcion desde test',
        ]);
    }

    /** @test */
    public function cant_update_articles()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('articles.update', $article), [])->assertJsonValidationErrors(['name', 'description']);

        $this->patchJson(route('articles.update', $article), [
            'name'        => 'Articulo editado',
            'description' => 'descripcion editado',
        ])
            ->assertJsonFragment([
                'name'        => 'Articulo editado',
                'description' => 'descripcion editado',
            ]);

        // verificamos en la BD
        $this->assertDatabaseHas('articles', [
            'name'        => 'Articulo editado',
            'description' => 'descripcion editado',
        ]);
    }

    /** @test */
    public function cant_delete_articles()
    {
        $article = Article::factory(10)->create();

        $this->deleteJson(route('articles.destroy', $article[0]))
            ->assertNoContent();

        $this->assertDatabaseCount('articles', 9);
    }
}
