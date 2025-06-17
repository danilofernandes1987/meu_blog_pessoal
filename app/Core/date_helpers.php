<?php
// app/Core/date_helpers.php

if (!function_exists('format_date_pt_br')) {
    /**
     * Formata uma data para o padrão Mês/Ano em português.
     * @param string|null $dateString A data no formato Y-m-d ou Y-m.
     * @return string A data formatada ou uma string vazia.
     */
    function format_date_pt_br($dateString)
    {
        if (empty($dateString)) {
            return '';
        }

        try {
            $timestamp = strtotime($dateString);
            if ($timestamp === false) {
                return $dateString; // Retorna a string original se a data for inválida
            }

            // IntlDateFormatter é a forma moderna e recomendada para formatação de datas
            // com base em localidade. Requer a extensão 'intl' do PHP.
            $formatter = new \IntlDateFormatter(
                'pt_BR', // Locale para Português do Brasil
                \IntlDateFormatter::NONE,
                \IntlDateFormatter::NONE,
                null, // Fuso horário (null usa o padrão do servidor)
                null, // Calendário (null usa o padrão)
                'MMM yyyy' // Padrão para "Mês abreviado Ano" (Ex: Dez 2025)
            );
            
            // ucfirst() para garantir que a primeira letra do mês seja maiúscula.
            return ucfirst($formatter->format($timestamp));
        } catch (\Exception $e) {
            // Retorna a data original se houver erro na formatação
            return $dateString;
        }
    }
}
