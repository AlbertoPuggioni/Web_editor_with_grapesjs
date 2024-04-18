<?php
session_start();
global $template_data;
global $template_id;

// Moduli:
// =================================================================================
// Autenticazione utente
include("../modules/authentication_user.php");
// Connessione con il db
include("../modules/connection_db.php");
// Logica che gestisce l'inserimento, la modifica e la cancellazione del template
include("../modules/template_manager.php");

// =================================================================================

?>
<!-- EDITOR DI GRAPESJS -->
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Per modificare l'icona nella tab del browser -->
    <link rel="icon" href="../img/editor.png" type="image/png">
    <!-- --------------------------------------------- -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Editor</title>

    <link rel="stylesheet" href="stylesheets/toastr.min.css">
    <link rel="stylesheet" href="stylesheets/grapes.min.css?v0.21.8">
    <link rel="stylesheet" href="stylesheets/grapesjs-preset-webpage.min.css">
    <link rel="stylesheet" href="stylesheets/tooltip.css">
    <link rel="stylesheet" href="stylesheets/demos.css?v3">
    <link href="https://unpkg.com/grapick/dist/grapick.min.css" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="scripts/toastr.min.js"></script>
    <script src="scripts/grapes.min.js?v0.21.8"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage@1.0.2"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms@2.0.5"></script>
    <script src="https://unpkg.com/grapesjs-component-countdown@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-plugin-export@1.0.11"></script>
    <script src="https://unpkg.com/grapesjs-tabs@1.0.6"></script>
    <script src="https://unpkg.com/grapesjs-custom-code@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-touch@0.1.1"></script>
    <script src="https://unpkg.com/grapesjs-parser-postcss@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-tooltip@0.1.7"></script>
    <script src="https://unpkg.com/grapesjs-tui-image-editor@0.1.3"></script>
    <script src="https://unpkg.com/grapesjs-typed@1.0.5"></script>
    <script src="https://unpkg.com/grapesjs-style-bg@2.0.1"></script>


    <link rel="stylesheet" href="//unpkg.com/grapesjs/dist/css/grapes.min.css">
    <link rel="stylesheet" type="text/css" href="web_editor_style.css">
    <script src="//unpkg.com/grapesjs"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script>
</head>

<body>
<div id="top-bar">
    <a href="../pages/home.php"><img src="../img/home.png" id="home"></a>
    <a href="../templates/collection_templates.php"> <img src="../img/collection.png" id="collection_templates"></a>
    <div id="save-wrapper">
        <input type="text" id="template-name" placeholder="Enter template name">
        <img src="../img/save.png" id="save">
    </div>
    <div class="user">
        <img src="../img/user.png" id="user">
        <div class="user-menu">
            <ul class="hover-menu">
                <li><a href="../pages/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- BLOCCO PER L'UPLOAD
<button id="load">Load your Code</button>
    <div id="code-input">
        <textarea id="html-input" placeholder="paste your html here"></textarea>
        <textarea id="css-input" placeholder="paste your css here"></textarea>
        <button id="apply-code"> Apply code</button>
    </div> -->
</div>


<div id="gjs" style="height:0px; overflow:hidden">
    <header class="header-banner">
        <div class="container-width">
            <div class="logo-container">
                <div class="logo">GrapesJS</div>
            </div>
            <nav class="menu">
                <div class="menu-item">BUILDER</div>
                <div class="menu-item">TEMPLATE</div>
                <div class="menu-item">WEB</div>
            </nav>
            <div class="clearfix"></div>
            <div class="lead-title">Template Drag and Drop</div>
            <div class="sub-lead-title">Edit your webpage with blocks. You can create new text blocks with the command from the left panel</div>
            <div class="lead-btn">Hover me</div>
        </div>
    </header>

    <section class="flex-sect">
        <div class="container-width">
            <div class="flex-title">Flex is the new black</div>
            <div class="flex-desc">With flexbox system you're able to build complex layouts easily and with free responsivity</div>
            <div class="cards">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <div class="card-title">Title one</div>
                        <div class="card-sub-title">Subtitle one</div>
                        <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header ch2"></div>
                    <div class="card-body">
                        <div class="card-title">Title two</div>
                        <div class="card-sub-title">Subtitle two</div>
                        <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header ch3"></div>
                    <div class="card-body">
                        <div class="card-title">Title three</div>
                        <div class="card-sub-title">Subtitle three</div>
                        <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header ch4"></div>
                    <div class="card-body">
                        <div class="card-title">Title four</div>
                        <div class="card-sub-title">Subtitle four</div>
                        <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header ch5"></div>
                    <div class="card-body">
                        <div class="card-title">Title five</div>
                        <div class="card-sub-title">Subtitle five</div>
                        <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header ch6"></div>
                    <div class="card-body">
                        <div class="card-title">Title six</div>
                        <div class="card-sub-title">Subtitle six</div>
                        <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="am-sect">
        <div class="container-width">
            <div class="am-container">
                <img class="img-phone" onmousedown="return false" src="./img/phone-app.png"/>
                <div class="am-content">
                    <div class="am-pre">ASSET MANAGER</div>
                    <div class="am-title">Manage your images with Asset Manager</div>
                    <div class="am-desc">You can create image blocks with the command from the left panel and edit them with double click</div>
                    <div class="am-post">Image uploading is not allowed in this demo</div>
                </div>
            </div>
        </div>
    </section>

    <section class="blk-sect">
        <div class="container-width">
            <div class="blk-title">Blocks</div>
            <div class="blk-desc">Each element in HTML page could be seen as a block. On the left panel you could find different kind of blocks that you can create, move and style</div>

            <div class="price-cards">
                <div class="price-card-cont">
                    <div class="price-card">
                        <div class="pc-title">Starter</div>
                        <div class="pc-desc">Some random list</div>
                        <div class="pc-feature odd-feat">+ Starter feature 1</div>
                        <div class="pc-feature">+ Starter feature 2</div>
                        <div class="pc-feature odd-feat">+ Starter feature 3</div>
                        <div class="pc-feature">+ Starter feature 4</div>
                        <div class="pc-amount odd-feat">$ 9,90/mo</div>
                    </div>
                </div>
                <div class="price-card-cont">
                    <div class="price-card pc-regular">
                        <div class="pc-title">Regular</div>
                        <div class="pc-desc">Some random list</div>
                        <div class="pc-feature odd-feat">+ Regular feature 1</div>
                        <div class="pc-feature">+ Regular feature 2</div>
                        <div class="pc-feature odd-feat">+ Regular feature 3</div>
                        <div class="pc-feature">+ Regular feature 4</div>
                        <div class="pc-amount odd-feat">$ 19,90/mo</div>
                    </div>
                </div>
                <div class="price-card-cont">
                    <div class="price-card pc-enterprise">
                        <div class="pc-title">Enterprise</div>
                        <div class="pc-desc">Some random list</div>
                        <div class="pc-feature odd-feat">+ Enterprise feature 1</div>
                        <div class="pc-feature">+ Enterprise feature 2</div>
                        <div class="pc-feature odd-feat">+ Enterprise feature 3</div>
                        <div class="pc-feature">+ Enterprise feature 4</div>
                        <div class="pc-amount odd-feat">$ 29,90/mo</div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="bdg-sect">
        <div class="container-width">
            <h1 class="bdg-title">The team</h1>
            <div class="badges">
                <div class="badge">
                    <div class="badge-header"></div>
                    <img class="badge-avatar" src="img/team1.jpg">
                    <div class="badge-body">
                        <div class="badge-name">Person 1</div>
                        <div class="badge-role">CEO</div>
                        <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>
                    </div>
                    <div class="badge-foot">
                        <span class="badge-link">f</span>
                        <span class="badge-link">t</span>
                        <span class="badge-link">ln</span>
                    </div>
                </div>
                <div class="badge">
                    <div class="badge-header"></div>
                    <img class="badge-avatar" src="img/team2.jpg">
                    <div class="badge-body">
                        <div class="badge-name">Person 2</div>
                        <div class="badge-role">Software Engineer</div>
                        <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>
                    </div>
                    <div class="badge-foot">
                        <span class="badge-link">f</span>
                        <span class="badge-link">t</span>
                        <span class="badge-link">ln</span>
                    </div>
                </div>
                <div class="badge">
                    <div class="badge-header"></div>
                    <img class="badge-avatar" src="img/team3.jpg">
                    <div class="badge-body">
                        <div class="badge-name">Person 3</div>
                        <div class="badge-role">Web Designer</div>
                        <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>
                    </div>
                    <div class="badge-foot">
                        <span class="badge-link">f</span>
                        <span class="badge-link">t</span>
                        <span class="badge-link">ln</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-under">
        <div class="container-width">
            <div class="footer-container">
                <div class="foot-lists">
                    <div class="foot-list">
                        <div class="foot-list-title">About us</div>
                        <div class="foot-list-item">Contact</div>
                        <div class="foot-list-item">Events</div>
                        <div class="foot-list-item">Company</div>
                        <div class="foot-list-item">Jobs</div>
                        <div class="foot-list-item">Blog</div>
                    </div>
                    <div class="foot-list">
                        <div class="foot-list-title">Services</div>
                        <div class="foot-list-item">Education</div>
                        <div class="foot-list-item">Partner</div>
                        <div class="foot-list-item">Community</div>
                        <div class="foot-list-item">Forum</div>
                        <div class="foot-list-item">Download</div>
                        <div class="foot-list-item">Upgrade</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-sub">
                    <div class="foot-form-cont">
                        <div class="foot-form-title">Subscribe</div>
                        <div class="foot-form-desc">Subscribe to our newsletter to receive exclusive offers and the latest news</div>
                        <input name="name" class="sub-input" placeholder="Name" />
                        <input name="email" class="sub-input" placeholder="Email"/>
                        <button class="sub-btn" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container-width">
                <div class="made-with">
                    made with GrapesJS
                </div>
                <div class="foot-social-btns">facebook twitter linkedin mail</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </footer>
    <link rel="stylesheet" type = "text/css" href="./stylesheets/assets.css">
</div>

<div id="info-panel" style="display:none">
    <br/>

    <div class="info-panel-label">
        <b>GrapesJS Webpage Builder</b> is a simple showcase of what is possible to achieve with the
        <a class="info-panel-link gjs-four-color" target="_blank" href="https://github.com/artf/grapesjs">GrapesJS</a>
        core library
        <br/><br/>
        For any hint about the demo check the
        <a class="info-panel-link gjs-four-color" target="_blank" href="https://github.com/artf/grapesjs-preset-webpage">Webpage Preset repository</a>
        and open an issue. For problems with the builder itself, open an issue on the main
        <a class="info-panel-link gjs-four-color" target="_blank" href="https://github.com/artf/grapesjs">GrapesJS repository</a>
        <br/><br/>
        Being a free and open source project contributors and supporters are extremely welcome.
        If you like the project support it with a donation of your choice or become a backer/sponsor via
        <a class="info-panel-link gjs-four-color" target="_blank" href="https://opencollective.com/grapesjs">Open Collective</a>
    </div>
</div>

<script src="web_editor_script.js"> </script>
<script>
    // Carica il template nel GrapesJS
    let html = `<?php echo $template_data['html']; ?>`;
    let css = `<?php echo $template_data['css']; ?>`;
    let imgURL = `<?php echo $template_data['imgURL']; ?>`;
    editor.setComponents(html);
    editor.setStyle(css);



    // Aggiunge l'evento click al pulsante "Save"
    $('#save').on('click', function () {
        let html = editor.getHtml();
        let css = editor.getCss();
        let templateName = $('#template-name').val();
        let iframe = document.querySelector("#gjs div div div div:nth-child(2) div iframe");
        let dociframe = iframe.contentDocument || iframe.contentWindow.document;
        let div = dociframe.querySelector("html");

        function snapshot() {
            return new Promise(function(resolve, reject) {
                html2canvas(div,
                    {
                        useCORS: true, //By passing this option in function Cross origin images will be rendered properly in the downloaded version of the PDF
                        onrendered: function (canvas) {

                        }
                    }
                ).then(function(canvas) {
                    // Ottieni l'URL dell'immagine
                    var imgData = canvas.toDataURL('image/png');
                    // Risolvi la Promise con l'URL dell'immagine
                    resolve(imgData);
                }).catch(function(error) {
                    // Se c'è un errore durante la creazione del canvas, rifiuta la Promise
                    reject(error);
                });
            });
        }

// Utilizza snapshot() per ottenere l'URL dell'immagine in modo asincrono
        snapshot().then(function(imgURL) {
            // Effettua una richiesta AJAX per salvare o aggiornare il template nel database
            $.ajax({
                url: 'web_editor.php?id=<?php echo $template_id; ?>',
                method: 'POST',
                data: { html: html, css: css, name: templateName, imgURL: imgURL },
                success: function (response) {
                    alert(response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            // Puoi procedere con l'utilizzo di imgURL come desiderato, ad esempio passandolo alla funzione AJAX
        }).catch(function(error) {
            // Gestisci eventuali errori qui
            console.error("Error in snapshot():", error);
        });
    });

</script>
</body>

</html>