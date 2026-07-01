# Arena de Combate RPG

Jogo de combate por turnos no terminal, desenvolvido em PHP com Programacao Orientada a Objetos.

## Como Executar

```bash
php game.php
```

Requer **PHP 8.1** ou superior.

## Tipos de Personagem

### Guerreiro
- **Atributos:** HP: 130 | Ataque: 18 | Defesa: 12 | Energia: 80
- **Habilidade Especial - Furia Berserker:** Desfere um golpe com o dobro do poder de ataque (com reducao parcial de defesa), mas perde 10% da vida maxima propria. Custo: 30 de energia.
- **Estrategia:** Alta vida e defesa, ideal para combates prolongados. A habilidade especial e arriscada mas devastadora.

### Mago
- **Atributos:** HP: 85 | Ataque: 14 | Defesa: 6 | Energia: 120
- **Habilidade Especial - Bola de Fogo:** Lanca um ataque magico que causa 38 de dano, ignorando completamente a defesa do oponente. Custo: 40 de energia.
- **Estrategia:** Fragil mas com alto potencial de dano magico. A energia elevada permite usar a habilidade especial com frequencia.

### Assassino
- **Atributos:** HP: 95 | Ataque: 16 | Defesa: 8 | Energia: 100
- **Habilidade Especial - Golpe Venenoso:** Ataca o oponente e aplica veneno que causa 8 de dano por turno durante 3 turnos. Custo: 25 de energia.
- **Estrategia:** Equilibrado com dano ao longo do tempo. O veneno acumula pressao sobre o oponente a cada turno.

## Acoes por Turno

1. **Atacar** - Ataque direto (dano = ataque - defesa do oponente, minimo 0)
2. **Defender** - Aumenta a defesa em +5 ate o proximo turno
3. **Habilidade Especial** - Habilidade unica de cada personagem (consome energia)

## Regras

- 2 jogadores no mesmo computador, alternando turnos
- Energia regenera +10 por turno
- A partida termina quando um personagem chega a 0 HP
- Ao final, e exibido um resumo com vencedor, turnos e log completo de combate

## Estrutura do Projeto

```
rpg-game/
  game.php                           # Ponto de entrada
  src/
    Arena.php                        # Loop principal do jogo
    Interfaces/
      Combatente.php                 # Interface de combate
    Exceptions/
      EnergiaInsuficienteException.php
      EntradaInvalidaException.php
    Personagens/
      Personagem.php                 # Classe abstrata base
      Guerreiro.php
      Mago.php
      Assassino.php
```
