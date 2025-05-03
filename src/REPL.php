<?php

namespace InterpretePHP;

class REPL
{
    public function run(): void
    {
        echo "Bienvenido al intérprete. Escribe 'salir' para terminar.\n";

        while (true) {
            $input = readline("> ");

            if (trim($input) === '') {
                continue;
            }

            if (strtolower(trim($input)) === 'salir') {
                break;
            }

            $lexer = new Lexer($input);
            $token = $lexer->nextToken();

            while ($token->type !== TokenType::EOF) {
                echo $token . "\n";
                $token = $lexer->nextToken();
            }

            echo "\n";
        }
    }
}