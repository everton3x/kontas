<?php

namespace Kontas\Routine\Receita;

class Adicionar extends \PTK\Console\Flow\Routine\RoutineAbstract {
    protected string $label = 'Adicionar';
    
    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        parent::__construct($program);
    }
    
    public function execute(): bool {
        $this->initialize();
        
        $periodo = new \PTK\Console\Form\Field\DateTimeField('periodo', 'Período', 'mY');
        $periodo->required(true);
        $periodo->setLabelInputFormat('mmaaaa');
        $periodo->setOutputFormat('Y-m');
        $periodo->ask();
        
        $descricao = new \PTK\Console\Form\Field\TextField('descricao', 'Descrição');
        $descricao->required(true);
        $descricao->ask();
        
        $devedor = new \PTK\Console\Form\Field\TextField('devedor', 'Devedor');
        $devedor->required(true);
        $devedor->ask();
        
        $this->program->console()->br()->out('Seleção da origem...');
        $options = [];
        $entity = new \Kontas\Entity\Origem($this->program);
        foreach ($entity->list() as $row) {
            $options[$row['id']] = $row['nome'];
        }
        $origem = new \PTK\Console\Form\Field\ChoiceField('origem', 'Selecione uma origem', $options);
        $origem->ask();
        
        $this->program->console()->br()->out('Seleção do centro de custo...');
        $options = [];
        $entity = new \Kontas\Entity\CentroDeCusto($this->program);
        foreach ($entity->list() as $row) {
            $options[$row['id']] = $row['nome'];
        }
        $cc = new \PTK\Console\Form\Field\ChoiceField('cc', 'Selecione um centro de custo', $options);
        $cc->ask();
        
        $vencimento = new \PTK\Console\Form\Field\DateTimeField('vencimento', 'Vencimento', 'dmY');
        $vencimento->required(false);
        $vencimento->setOutputFormat('Y-m-d');
        $vencimento->setLabelInputFormat('ddmmaaaa');
        $vencimento->setDefault($periodo->object());
        $vencimento->ask();
        
        $valor = new \PTK\Console\Form\Field\NumberField('valor', 'Valor');
        $valor->required(true);
        $valor->setMin(0.01);
        $valor->setDecimals(2);
        $valor->setDecimalSeparator(',');
        $valor->setThousandsSeparator('.');
        $valor->ask();
        
        $agrupador = '';
        $parcela = 0;
        
        $this->program->console()->info('Confirma os dados?');
        $this->program->console()->inline('Periodo:')->tab()->bold()->out($periodo->object()->format('m/Y'));
        $this->program->console()->inline('Descrição:')->tab()->bold()->out($descricao->answer());
        $this->program->console()->inline('Origem:')->tab()->bold()->out($origem->answer());
        $this->program->console()->inline('Devedor:')->tab()->bold()->out($devedor->answer());
        $this->program->console()->inline('Centro de Custo:')->tab()->bold()->out($cc->answer());
        $this->program->console()->inline('Vencimento:')->tab()->bold()->out($vencimento->answer());
        $this->program->console()->inline('Valor:')->tab()->bold()->out($valor->answer());
        
        $salvar = new \PTK\Console\Form\Field\YesNoField('salvar', 'Confirma os dados');
        $salvar->ask();
        if($salvar->answer() === false){
            $this->program->console()->error('Adição abortada...');
            $result = false;
        }
        
        
        $this->finalize();
        $this->program->pause();
        return $result;
    }
}
