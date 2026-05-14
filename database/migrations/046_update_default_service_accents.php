<?php

return [
    'up' => "
        UPDATE services SET
            name = 'Painel de Gestão para Igrejas',
            description = 'Sistema completo para membros, finanças, ministérios, eventos, relatórios e rotina pastoral. Inclui até 100 usuários da plataforma de gestão.',
            rules = 'Acesso por assinatura da igreja responsável. Acima de 100 usuários pode haver custo adicional.'
        WHERE slug = 'painel-gestao-igrejas';

        UPDATE services SET
            description = 'Construtor de site institucional com modelos, dados cadastrais, preview e publicação para assinantes.',
            rules = 'Plano avulso de site por R$ 67,00/mês. No combo com gestão, o total fica R$ 99,90/mês.'
        WHERE slug = 'site-para-igrejas';

        UPDATE services SET
            description = 'Criação assistida de sermões, estudos bíblicos, séries, ministrações e planos de leitura.',
            rules = 'Materiais publicados aparecem no sistema de gestão e na área do membro.'
        WHERE slug = 'central-pastoral-ia';

        UPDATE services SET
            description = 'Apoio para elegibilidade, configuração e gestão de campanhas para ONGs e igrejas.',
            rules = 'Disponibilidade depende das regras do programa e validação da instituição.'
        WHERE slug = 'google-ad-grants';

        UPDATE services SET
            description = 'Orientação para ativar ferramentas Google Workspace e recursos para organizações elegíveis.',
            rules = 'Sujeito a aprovação externa do programa.'
        WHERE slug = 'google-para-ongs';

        UPDATE services SET
            name = 'Gestão de Tráfego Pago',
            description = 'Planejamento, criação e acompanhamento de campanhas pagas para comunicação e captação.',
            rules = 'Investimento de mídia não incluso no serviço.'
        WHERE slug = 'gestao-trafego-pago';

        UPDATE services SET
            description = 'Apoio para identificar benefícios, licenças e oportunidades de tecnologia para organizações.',
            rules = 'Sujeito às regras e disponibilidade dos parceiros.'
        WHERE slug = 'techsoup-brasil';

        UPDATE services SET
            description = 'Apoio na estruturação de ferramentas colaborativas, design e produtividade para equipes.',
            rules = 'Benefícios dependem da elegibilidade da instituição.'
        WHERE slug = 'microsoft-canva-slack';

        UPDATE services SET
            name = 'Implantação Acompanhada',
            description = 'Acompanhamento para configurar dados iniciais, equipe, módulos e rotina de adoção.'
        WHERE slug = 'implantacao-acompanhada';

        UPDATE services SET
            name = 'Diagnóstico Organizacional',
            description = 'Mapeamento de processos, comunicação, governança e oportunidades de melhoria.',
            rules = 'Pode exigir reunião de levantamento com responsáveis.'
        WHERE slug = 'diagnostico-organizacional';

        UPDATE services SET
            name = 'Workshop de Capacitação',
            description = 'Treinamentos para liderança, comunicação, tecnologia e uso da plataforma.',
            rules = 'Formato e duração definidos por demanda.'
        WHERE slug = 'workshop-capacitacao'
    ",
    'down' => "",
];
