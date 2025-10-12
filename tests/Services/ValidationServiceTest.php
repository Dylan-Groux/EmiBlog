<?php
use App\Services\ValidationService;
use App\Models\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe ValidationService.
 * @var ValidationService
 */
class ValidationServiceTest extends TestCase
{
    public function testCheckNotEmptyFieldsThrowsExceptionIfFieldIsEmpty()
    {
        $this->expectException(ValidationException::class);
        ValidationService::checkNotEmptyFields([
            'id_user' => 1,
            'title' => '',
            'content' => 'Contenu'
        ]);
    }

    public function testCheckNotEmptyFieldsPassesIfAllFieldsFilled()
    {
        $this->assertNull(
            ValidationService::checkNotEmptyFields([
                'id_user' => 1,
                'title' => 'Titre',
                'content' => 'Contenu'
        ]));
    }
}
