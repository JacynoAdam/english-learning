<?php
global $words;
global $current;
?>
<div class="bg-dark text-light p-3 mx-auto mb-2">
    <div class="text-center">
        <h4>Incorrect! The answer was "<?= $words[$current]->getName() ?>"</h4>
    </div>
    <hr class="dark-hr">
    <div class="text-center mt-1">
        <button onclick="learn('load')" id="confirm" class="btn btn-primary">Next</button>
    </div>
</div>