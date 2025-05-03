<?php

namespace InterpretePHP;

class Lexer
{
    private string $source;
    private ?string $currentChar;
    private int $position;
    private int $readPosition;

    public function __construct(string $source)
    {
        $this->source = $source;
        $this->position = 0;
        $this->readPosition = 0;
        $this->readChar();
    }

    public function nextToken(): Token
    {
        $this->skipWhitespace();

        $token = match ($this->currentChar) {
            '=' => $this->peekChar() === '=' ? ($this->readChar() && new Token(TokenType::EQ, '==')) : new Token(TokenType::ASSIGN, $this->currentChar),
            '!' => $this->peekChar() === '=' ? ($this->readChar() && new Token(TokenType::NOT_EQ, '!=')) : new Token(TokenType::BANG, $this->currentChar),
            '+' => new Token(TokenType::PLUS, $this->currentChar),
            '-' => new Token(TokenType::MINUS, $this->currentChar),
            '*' => new Token(TokenType::ASTERISK, $this->currentChar),
            '/' => new Token(TokenType::SLASH, $this->currentChar),
            '<' => $this->peekChar() === '=' ? ($this->readChar() && new Token(TokenType::LE, '<=')) : new Token(TokenType::LT, $this->currentChar),
            '>' => $this->peekChar() === '=' ? ($this->readChar() && new Token(TokenType::GE, '>=')) : new Token(TokenType::GT, $this->currentChar),
            '(' => new Token(TokenType::LPAREN, $this->currentChar),
            ')' => new Token(TokenType::RPAREN, $this->currentChar),
            '{' => new Token(TokenType::LBRACE, $this->currentChar),
            '}' => new Token(TokenType::RBRACE, $this->currentChar),
            ',' => new Token(TokenType::COMMA, $this->currentChar),
            ';' => new Token(TokenType::SEMICOLON, $this->currentChar),
            '"' => new Token(TokenType::QUOTATION, $this->currentChar),
            null => new Token(TokenType::EOF, ''),
            default => $this->isDigit($this->currentChar) ? $this->readNumber() : ($this->isLetter($this->currentChar) ? $this->readLiteral() : new Token(TokenType::ILLEGAL, $this->currentChar)),
        };

        $this->readChar();
        return $token;
    }

    private function skipWhitespace(): void
    {
        while (ctype_space($this->currentChar)) {
            $this->readChar();
        }
    }

    private function readChar(): void
    {
        if ($this->readPosition >= strlen($this->source)) {
            $this->currentChar = null;
        } else {
            $this->currentChar = $this->source[$this->readPosition];
        }
        $this->position = $this->readPosition;
        $this->readPosition++;
    }

    private function peekChar(): ?string
    {
        if ($this->readPosition >= strlen($this->source)) {
            return null;
        }
        return $this->source[$this->readPosition];
    }

    private function isDigit(?string $c): bool
    {
        return $c !== null && ctype_digit($c);
    }

    private function isLetter(?string $c): bool
    {
        return $c !== null && ctype_alpha($c);
    }

    private function readNumber(): Token
    {
        $start = $this->position;
        while ($this->isDigit($this->currentChar)) {
            $this->readChar();
        }
        return new Token(TokenType::INT, substr($this->source, $start, $this->position - $start));
    }

    private function readLiteral(): Token
    {
        $start = $this->position;
        while ($this->isLetter($this->currentChar) || $this->isDigit($this->currentChar)) {
            $this->readChar();
        }
        return new Token(TokenLookup::lookupTokenType(substr($this->source, $start, $this->position - $start)), substr($this->source, $start, $this->position - $start));
    }
}