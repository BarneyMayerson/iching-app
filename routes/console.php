<?php

use Illuminate\Support\Facades\Schedule;

// Делаем "снимок" состояния очередей каждые 5 минут для графиков в дашборде
Schedule::command('horizon:snapshot')->everyFiveMinutes();

// Очищаем старые данные о выполненных задачах
Schedule::command('horizon:terminate')->daily();

// Удаляем записи о проваленных задачах старше 7 дней
Schedule::command('queue:prune-failed', ['--hours' => 168])->daily();

// Удаляем старые пакеты задач
Schedule::command('queue:prune-batches', ['--hours' => 48])->daily();
