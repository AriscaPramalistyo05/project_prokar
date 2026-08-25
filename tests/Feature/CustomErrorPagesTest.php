<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomErrorPagesTest extends TestCase
{
    public function test_all_custom_error_views_render_successfully(): void
    {
        $statusCodes = [401, 403, 404, 419, 429, 500, 503];

        foreach ($statusCodes as $code) {
            $view = view("errors.{$code}", [
                'exception' => new \Exception("Pesan simulasi error {$code}"),
            ]);

            $html = $view->render();
            $this->assertNotEmpty($html);
            $this->assertStringContainsString("Error {$code}", $html);
        }
    }

    public function test_404_error_page_on_non_existent_route(): void
    {
        $response = $this->get('/halaman-acak-pasti-tidak-ada-12345');
        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman Tidak Ditemukan');
    }
}
