<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Program;

class ProgramController extends Controller
{
    public function index(): void
    {
        $this->view('public/programs/index', [
            'title' => 'Chương trình đào tạo',
            'programs' => (new Program())->allActive(),
        ]);
    }

    public function show(string $slug): void
    {
        $programModel = new Program();
        $program = $programModel->findActiveBySlug($slug);
        if ($program === null) {
            $this->notFound();
        }

        $otherPrograms = array_values(array_filter(
            $programModel->allActive(),
            fn (array $otherProgram): bool => (int) $otherProgram['id'] !== (int) $program['id']
        ));

        $this->view('public/programs/show', [
            'title' => $program['ten'],
            'program' => $program,
            'otherPrograms' => $otherPrograms,
        ]);
    }
}
