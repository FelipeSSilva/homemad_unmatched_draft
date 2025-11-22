<?php

require_once 'boot.php';

if (!isset($_GET['id'])) {
    $draft = null;
} else {
    $draft = \App\Draft::load($_GET['id']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $draft ? $draft->name() . ' | ' : '' ?>Unmatched Draft</title>
    <link rel="stylesheet" href="<?= url('css/style.css?v=' . $_ENV['VERSION']) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,600;1,300&family=Staatliches&display=swap" rel="stylesheet">


    <meta property="og:image" content="<?= url('og.png') ?>" />

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#1a2266">
    <meta name="msapplication-TileColor" content="#fdfcf8">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <div class="container">

        <?php if ($draft) : ?>
            <h1><?= $draft->name() ?></h1>
            <h2>Unmatched Draft</h2>
        <?php else : ?>
            <h1>Unmatched Draft</h1>
        <?php endif; ?>

        <div id="tabs">
            <nav>
                <div class="content-wrap">
                    <div class="left">
                        <a class="active" href="#draft">Draft</a>
                        <a href="#log">Log</a>
                        <a href="#config">Config</a>
                        <a href="#session">Session</a>
                    </div>
                </div>
            </nav>
            <div class="tab active" id="draft">
                <div class="content-wrap">
                    <?php if ($draft == null || $draft == false) : ?>
                        <h2 class="error">Draft not found. (or something else went wrong)</h2>
                    <?php else : ?>
                        <div class="status" id="turn">
                            <p>It's <span id="current-name">x's</span> turn to draft something. <span id="admin-msg">You are the admin so you can do this for them.</span></p>
                        </div>
                        <div class="status" id="done">
                            <p>This draft is over!</p>
                        </div>

                        <div class="players">
                            <?php foreach (array_values($draft->players()) as $i => $player) : ?>
                                <div id="player-<?= $player['id'] ?>" class="player">
                                    <h3><span><?= $i + 1 ?></span> <?= $player['name'] ?></h3>

                                    <span class="you" data-id="<?= $player['id'] ?>">you</span>
                                    <p>
                                        <strong>Heroes:</strong> <br /> <span class="chosen-hero">? ? ? ? ?</span><br />
                                        <strong>Banned Map:</strong> <span class="chosen-map">?</span><br />
                                    </p>
                                    <p class="center">
                                        <button class="claim" data-id="<?= $player['id'] ?>">Claim</button>
                                        <button class="unclaim" data-id="<?= $player['id'] ?>">Unclaim</button>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="players" id="randommap">
                            <?php foreach (array_values($draft->players()) as $i => $player) : ?>
                                <div class="player">
                                    <h3><span><?= $i + 1 ?></span> Random Maps </h3>
                                    <p id="randommap-<?= $i ?>">
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="slices draft-options">
                            <h3>Heroes</h3>
                            <div class="options">
                                <?php foreach ($draft->heroes() as $heroSlug => $heroData) : ?>
                                    <div class="slice option" data-slug="<?= $heroSlug ?>">
                                        <div class="slice-graph">
                                            <div class="wrap">
                                                <img class="tile-<?= $heroSlug ?>" src="<?= url('img/heroes/' . $heroSlug . '.png') ?>" />
                                            </div>
                                        </div>

                                        <div class="slice-info">
                                            <h4><?= $heroData['name'] ?></h4>
                                            <a target="_blank" href="https://www.the-unmatched.club/heroes/<?= $heroSlug ?>" class="more">[info]</a><br />

                                            <p class="center">
                                                <button class="draft" data-category="hero" data-value="<?= $heroData['name'] ?>">Draft</button>
                                                <span class="drafted-by" data-category="hero" data-value="<?= $heroData['name'] ?>"></span>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="factions draft-options">
                            <h3>Maps</h3>
                            <div class="options">
                                <?php foreach ($draft->maps() as $map) : ?>

                                    <div class="faction option">

                                            <img src="<?= url('img/maps/' . $map['slug'] . '.png') ?>" /><br />

                                            <span><?= $map['name'] ?></span><br />
                                            <p class="resource-count">
                                                Spaces: <?= $map['spaces'] ?>
                                            </p>
                                            <?php foreach ($map['sets_names'] as $setName) : ?>
                                                <p class="resource-count">
                                                    Set: <?= $setName ?>
                                                </p>
                                            <?php endforeach; ?>
                                            <a target="_blank" href="https://www.the-unmatched.club/maps/<?= $map['slug'] ?>" class="more">[info]</a><br />
                                            <button class="draft ban" data-category="map" data-value="<?= $map['name'] ?>">Ban</button>
                                            <span class="drafted-by" data-category="map" data-value="<?= $map['name'] ?>"></span>


                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <script>
                            window.draft = <?= $draft; ?>;
                        </script>

                    <?php endif; ?>
                </div>
            </div>

            <?php $config = $draft->config(); ?>
            <div class="tab" id="regen">
                <div class="content-wrap">
                    <?php if (empty($draft->log())) : ?>
                        <p id="regen-options">
                            <label for="shuffle_slices"><input type="checkbox" checked id="shuffle_slices" name="shuffle_slices" /> New Slices</label>
                            <label for="shuffle_factions"><input type="checkbox" checked id="shuffle_factions" name="shuffle_factions" /> New Factions</label>
                            <label for="shuffle_order"><input type="checkbox" id="shuffle_order" name="shuffle_order" /> New <?= (($config->alliance["alliance_teams"] ?? "") == 'random') ? 'teams and ' : '' ?>player order</label>
                            <button id="regenerate" class="btn">Regenerate</button>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab" id="config">
                <div class="content-wrap">
                    <h3>Configuration used</h3>

                    <p>
                        <label>Number of Players:</label> <strong><?= count($draft->players()) ?></strong>
                    </p>
                    <p>
                        <label>Sets:</label> <strong>
                            <?php if ($config->sets != null) : ?>
                                <?php foreach ($config->sets as $set) : ?>
                                    <?= $set ?><br />
                                <?php endforeach; ?>
                            <?php else : ?>
                                no
                            <?php endif; ?>
                        </strong>
                    </p>
                </div>
            </div>
            <div class="tab" id="log">
                <div class="content-wrap">
                    <h3>Log</h3>
                    <div id="log-content"></div>
                    <br>
                    <button class="undo-last-action">Undo last action</button>
                </div>
            </div>
            <div class="tab" id="session">
                <div class="content-wrap">
                  <div id="current-session">
                      <h3>Session</h3>
                      <p>Make sure to save your passkey so you can restore your session if it is lost (e.g., cleared cache). The passkey is also useful if you want to draft on another device.</p>
                      <p id="current-session-admin">
                          <label>Admin Passkey:</label><strong></strong>
                      </p>
                      <p id="current-session-player">
                          <label>Passkey:</label><strong></strong>
                      </p>
                      <br>
                  </div>
                  <div>
                      <h3>Restore Session</h3>
                      <form id="secret-form" action="restore.php" method="post">
                          <div class="secret_input_section">
                              <p class="secret_label">Restore a session to be able to draft in this device.</p>
                              <div class="input secret">
                                  <input type="text" placeholder="Passkey" name="secret" />
                              </div>
                          </div>
                          <p>
                              <button type="submit" id="submit">Restore</button>
                          </p>
                      </form>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" id="confirm-popup">
        <div class="content">
            <p>
                Are you sure you wish to choose the following <span id="confirm-category"></span>: <span id="confirm-value"></span>.<br />
                This can only be undone by the creator of your draft.
            </p>
            <p>
                <button id="confirm">Confirm</button>
                <button id="confirm-cancel">Cancel</button>
            </p>
        </div>
    </div>

    <div class="popup" id="reference-popup">
        <a class="btn close-reference invert">&times;</a>
        <img data-base="<?= url('img/reference/r_') ?>" src="" />
    </div>

    <div class="popup" id="error-popup">
        <div class="content">
            <p>
                Something went wrong. Maybe you left this tab open too long and the data is outdated. Try refreshing and trying again.
            </p>
            <p>Error: <span id="error-message"></span></p>
            <p>
                <button id="close-error">Ok</button>
            </p>

        </div>
    </div>


    <div class="overlay" id="loading">
        Loading. Please wait.<br />
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="0" fill="none" stroke="#fefcf8" stroke-width="2">
                <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="0s"></animate>
                <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="0s"></animate>
            </circle>
            <circle cx="50" cy="50" r="0" fill="none" stroke="#fefcf8" stroke-width="2">
                <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="-0.5s"></animate>
                <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="-0.5s"></animate>
            </circle>
        </svg>
    </div>

    <div class="popup" id="session-popup">
        <div class="content">
            <a class="btn invert close-popup">&times;</a>
            <p id="admin">Your admin passkey is <strong id="popup-admin-passkey">SOME PASSKEY</strong></p>
            <p id="user">Your passkey is <strong id="popup-passkey">SOME PASSKEY</strong></p>
            <p>Write this down somewhere. You can read more about passkeys in the SESSION tab</p>
        </div>
    </div>

    <script>
        window.routes = {
            "claim": "<?= url('claim.php') ?>",
            "pick": "<?= url('pick.php') ?>",
            "regenerate": "<?= url('generate.php') ?>",
            "data": "<?= url('data.php') ?>",
            "undo": "<?= url('undo.php') ?>",
            "restore": "<?= url('restore.php') ?>"
        }
    </script>
    <script src="<?= url('js/vendor.js?v=' . $_ENV['VERSION']) ?>"></script>
    <script src="<?= url('js/draft.js?v=' . $_ENV['VERSION']) ?>"></script>
    <script src="<?= url('js/generate-map.js?v=' . $_ENV['VERSION']) ?>"></script>
</body>

</html>
