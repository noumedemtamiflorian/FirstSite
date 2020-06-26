<?= $renderer->render('header') ?>
    <h1>Bienvenue sur le blog</h1>
    <ul>
        <?php for ($i = 0; $i < 10; $i++) : ?>
            <li><a href=<?= $router->generateUri('blog.show', ['slug' => 'zaeaze-'.$i]); ?>>Article <?= $i + 1 ?> </a></li>
        <?php endfor; ?>
    </ul>
<?= $renderer->render('footer') ?>
