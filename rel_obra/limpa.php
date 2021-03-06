<?php


$pasta = $_POST['caminho'];

function apagarTudo ($dir) {

    if (is_dir($dir)) {

        $iterator = new \FilesystemIterator($dir);

        if ($iterator->valid()) {

            $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ( $ri as $file ) {

                $file->isDir() ?  rmdir($file) : unlink($file);
            }
        }
    }
}

apagarTudo($pasta);

?>