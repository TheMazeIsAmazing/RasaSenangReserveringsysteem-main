<?php function oneDotOrMoreFooter($dotsString) {
    $footer = '© '. date("Y") . ' - Rasa Senang Dordrecht.';?>
<footer>
    <section>
        <div> <?= $footer ?> </div>
        <div> <a href="<?= $dotsString ?>/privacystatement"> Privacystatement </a> </div>
    </section>
</footer>

<?php } ?>