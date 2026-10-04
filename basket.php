<?php

declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];

/**
 * Выводит список покупок на экран.
 */
function printBasket(array $items): void
{
    if (count($items) > 0) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode("\n", $items) . PHP_EOL;
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}

/**
 * Выводит список покупок и запрашивает номер операции.
 * Повторяет запрос, пока пользователь не введёт корректный номер.
 */
function askOperation(array $items, array $operations): int
{
    do {
        system('clear');
        // system('cls'); // windows

        printBasket($items);

        echo 'Выберите операцию для выполнения: ' . PHP_EOL;

        // Если список пуст, пункт про удаление не показываем
        $availableOperations = $operations;
        if (count($items) === 0) {
            unset($availableOperations[OPERATION_DELETE]);
        }

        echo implode(PHP_EOL, $availableOperations) . PHP_EOL . '> ';

        $operationNumber = trim((string) fgets(STDIN));

        if (array_key_exists($operationNumber, $availableOperations)) {
            return (int) $operationNumber;
        }

        echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL . PHP_EOL;
    } while (true);
}

/**
 * Запрашивает название товара и добавляет его в список покупок.
 */
function addItem(array &$items): void
{
    echo 'Введите название товара для добавления в список: ' . PHP_EOL . '> ';
    $itemName = trim((string) fgets(STDIN));

    $items[] = $itemName;
}

/**
 * Запрашивает название товара и удаляет его из списка покупок.
 * Если товара в списке нет, удалять нечего — сообщаем об этом.
 */
function deleteItem(array &$items): void
{
    if (count($items) === 0) {
        echo 'Список покупок пуст — удалять нечего. Выберите другую операцию.' . PHP_EOL;
        return;
    }

    printBasket($items);

    echo 'Введите название товара для удаления из списка: ' . PHP_EOL . '> ';
    $itemName = trim((string) fgets(STDIN));

    while (($key = array_search($itemName, $items, true)) !== false) {
        unset($items[$key]);
    }
}

/**
 * Отображает список покупок с количеством позиций.
 */
function showItems(array $items): void
{
    printBasket($items);

    echo 'Всего ' . count($items) . ' позиций. ' . PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

do {
    $operationNumber = askOperation($items, $operations);

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem($items);
            break;

        case OPERATION_DELETE:
            deleteItem($items);
            break;

        case OPERATION_PRINT:
            showItems($items);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;
