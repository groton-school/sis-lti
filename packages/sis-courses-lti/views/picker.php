<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose a Section</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@groton/colors@0.2.0/vars.css" />
</head>

<body>
    <div id="modal" class="modal fade" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Choose a section</h5>
                </div>
                <div class="modal-body">
                    <?php
                    foreach ($sections as $name => $url) {
                        $style = "";
                        preg_match("/\((.+ )?(RD|OR|YL|GR|LB|DB|PR)( .+)?\)$/", $name, $match);
                        if (!empty($match[2])) {
                            $color = strtolower($match[2]);
                            $style = "style=\"background: var(--$color); color: var(--text-on-$color);\"";
                        }
                        ?>
                        <div>
                            <a href="<?= $url ?>" class="btn btn-secondary m-3" <?= $style ?> target="_top"><?= $name ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        bootstrap.Modal.getOrCreateInstance('#modal').show()
    </script>
</body>

</html>