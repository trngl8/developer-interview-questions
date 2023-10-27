<?php

namespace App\Tests;

use App\Database;
use App\Question;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class QuestionTest extends TestCase
{
    private $dotenv;

    private $database;

    private string $kernelProjectDir = __DIR__.'/../';

    public function setUp(): void
    {
        parent::setUp();
        $this->dotenv = new Dotenv();
        $this->dotenv->load(__DIR__.'/../.env', __DIR__.'/../.env.test');
        $dsn = $_ENV['DATABASE_DSN'];
        $matches = [];
        preg_match('/^([a-z]+):\/\/(.+)$/', $dsn, $matches);
        switch ($matches[1]) { // scheme
            case 'postgres':
                $params = parse_url($dsn);
                $this->database = new Database(sprintf("pgsql:host=%s;port=%d;dbname=%s", $params['host'], $params['port'], str_replace('/', '', $params['path'])),
                    $params['user'],
                    $params['pass']
                );
                break;
            case 'sqlite':
                $this->database = new Database(sprintf("sqlite:%s", str_replace('%kernel.project_dir%', $this->kernelProjectDir, $matches[2])));
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unsupported database type: %s', $matches[1]));
        }


    }

    public function testQuestion(): void
    {
        $model = new Question($this->database);
        $records = $model->getQuestions();
        $this->assertGreaterThan(0, count($records));
    }
}
