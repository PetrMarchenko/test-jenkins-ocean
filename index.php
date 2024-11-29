<?php
if ($argc < 2) {
    echo "Не указан сервер для проверки.\n";
    exit(1);
}

$public_key = $argv[1];

$url = "https://incentive-backend.oceanprotocol.com/nodes?page=1&size=10&search=" . $public_key;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


$response = curl_exec($ch);

if ($response === false) {
    curl_close($ch);
    echo "Ошибка при обращении к серверу $public_key.\n";
    exit(1);
}

curl_close($ch);


$node_data = json_decode($response, true);


if (!$node_data) {
    echo "Неверный формат данных от сервера $public_key.\n";
    exit(1);
}


if (!isset($node_data['nodes'][0]['_source']['eligible'])) {
    echo "Сервер $public_key не имеет необходимого состояния.\n";
    exit(1);
}


$node_status = $node_data['nodes'][0]['_source']['eligible'] ? 'active' : 'inactive';

echo "Сервер $public_key имеет состояние: $node_status.\n";


exit($node_status === 'active' ? 0 : 1);


