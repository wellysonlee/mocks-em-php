<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Dao\Leilao as DaoLeilao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;

class LeilaoDao extends TestCase
{
    
    private static \PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        self::$pdo->exec('create table leiloes(
            id INTEGER primary key,
            descricao TEXT,
            finalizado BOOL, 
            dataInicio TEXT);'); 
    }
    protected function setUp():void
    {
        self::$pdo->beginTransaction();
    }
    public function testInsercaoEBuscaDevemFuncionar () 
    {
        //arraneg
        $leilao = new Leilao('Variante 0Km');
        $leilaoDao = new DaoLeilao (self::$pdo);
        $leilaoDao->salva($leilao);

        //act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        //assert
        self::assertCount(1,$leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Variante 0Km',$leiloes[0]->recuperarDescricao());
    }

    protected function tearDown(): void
    {
        self::$pdo->rollBack();

    }
}