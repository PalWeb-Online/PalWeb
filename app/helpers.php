<?php

function glossParser($gloss)
{

    $gloss = str_replace('IND', '<span class="yel">IND</span>', $gloss);
    $gloss = str_replace('DEF', '<span class="grn">DEF</span>', $gloss);

    return $gloss;
}
