<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'saveScene') {
        $username = $_POST['username'];
        $sceneData = $_POST['sceneData'];

        $stmt = $pdo->prepare("INSERT INTO scenes (username, scene_data) VALUES (?, ?)");
        if ($stmt->execute([$username, $sceneData])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save scene.']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'];

    if ($action === 'getScenes') {
        $username = $_GET['username'];

        $stmt = $pdo->prepare("SELECT id, scene_data FROM scenes WHERE username = ?");
        $stmt->execute([$username]);
        $scenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formattedScenes = array_map(function($scene) {
            $sceneData = json_decode($scene['scene_data'], true);
            return [
                'id' => $scene['id'],
                'name' => "Scene " . $scene['id'] . " (" . $sceneData['objectType'] . ")"
            ];
        }, $scenes);

        echo json_encode(['success' => true, 'scenes' => $formattedScenes]);
    } elseif ($action === 'getScene') {
        $sceneId = $_GET['sceneId'];

        $stmt = $pdo->prepare("SELECT scene_data FROM scenes WHERE id = ?");
        $stmt->execute([$sceneId]);
        $scene = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($scene) {
            echo json_encode(['success' => true, 'sceneData' => $scene['scene_data']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Scene not found.']);
        }
    }
}
?>
