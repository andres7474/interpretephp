<?php

namespace InterpretePHP;

enum TokenType: string
{
    case ASSIGN = '=';
    case BANG = '!';
    case COMMA = ',';
    case EOF = 'EOF';
    case EQ = '==';
    case IF = 'if';
    case ELSE = 'else';
    case NOT_EQ = '!=';
    case FOR = 'for';
    case FUNCTION = 'function';
    case IDENT = 'IDENT';
    case ILLEGAL = 'ILLEGAL';
    case INT = 'INT';
    case LBRACE = '{';
    case LET = 'let';
    case LPAREN = '(';
    case PLUS = '+';
    case MINUS = '-';
    case ASTERISK = '*';
    case SLASH = '/';
    case LT = '<';
    case GT = '>';
    case LE = '<=';
    case GE = '>=';
    case RBRACE = '}';
    case RPAREN = ')';
    case SEMICOLON = ';';
    case WHILE = 'while';
    case QUOTATION = '"';
}

class Token
{
    public TokenType $type;
    public string $literal;

    public function __construct(TokenType $type, string $literal)
    {
        $this->type = $type;
        $this->literal = $literal;
    }

    public function __toString(): string
    {
        return "Token({$this->type->value}, {$this->literal})";
    }
}

class TokenLookup
{
    private static array $keywords = [
        'function' => TokenType::FUNCTION,
        'let' => TokenType::LET,
        'if' => TokenType::IF,
        'else' => TokenType::ELSE,
        'for' => TokenType::FOR,
        'while' => TokenType::WHILE,
    ];

    public static function lookupTokenType(string $literal): TokenType
    {
        return self::$keywords[$literal] ?? TokenType::IDENT;
    }
}