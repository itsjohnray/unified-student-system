<?php
/**
 * Database-backed session handler for serverless (Vercel) deployment.
 * Required because Vercel serverless functions don't share a filesystem,
 * so PHP's default file-based sessions won't persist between requests.
 */
class DbSessionHandler implements SessionHandlerInterface {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function open($path, $name): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string|false {
        $stmt = $this->conn->prepare("SELECT `data` FROM `sessions` WHERE `id` = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['data'];
        }
        return '';
    }

    public function write($id, $data): bool {
        $time = time();
        $stmt = $this->conn->prepare("REPLACE INTO `sessions` (`id`, `data`, `timestamp`) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $id, $data, $time);
        return $stmt->execute();
    }

    public function destroy($id): bool {
        $stmt = $this->conn->prepare("DELETE FROM `sessions` WHERE `id` = ?");
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    }

    public function gc($max_lifetime): int|false {
        $old = time() - $max_lifetime;
        $stmt = $this->conn->prepare("DELETE FROM `sessions` WHERE `timestamp` < ?");
        $stmt->bind_param("i", $old);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}

function initDbSessions($conn) {
    // Automatically create the sessions table if it doesn't exist to prevent deployment errors
    $conn->query("
        CREATE TABLE IF NOT EXISTS `sessions` (
            `id` VARCHAR(128) NOT NULL PRIMARY KEY,
            `data` TEXT NOT NULL,
            `timestamp` INT UNSIGNED NOT NULL,
            INDEX `idx_timestamp` (`timestamp`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");

    $handler = new DbSessionHandler($conn);
    session_set_save_handler($handler, true);
}
?>
