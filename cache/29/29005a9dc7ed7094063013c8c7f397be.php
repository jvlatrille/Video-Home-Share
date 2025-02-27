<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* base_template.html.twig */
class __TwigTemplate_f2f8ef483a9305bc8044d06792db3699 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'css' => [$this, 'block_css'],
            'title' => [$this, 'block_title'],
            'content1' => [$this, 'block_content1'],
            'content2' => [$this, 'block_content2'],
            'content' => [$this, 'block_content'],
            'scripts' => [$this, 'block_scripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"";
        // line 2
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("WEBSITE_LANGUAGE"), "html", null, true);
        yield "\">
\t<head>
\t\t<!-- Bootstrap JS -->
\t\t<script src=\"node_modules/tarteaucitronjs/tarteaucitron.js\"></script>
\t\t<script src=\"node_modules/bootstrap/dist/js/bootstrap.bundle.js\"></script>
\t\t<script type=\"text/javascript\">
\t\t\ttarteaucitron.init({
\t\t\t\t\"privacyUrl\": \"\", /* Privacy policy url */
\t\t\t\t\"bodyPosition\": \"bottom\", /* or top to bring it as first element for accessibility */
\t\t\t\t\"hashtag\": \"#tarteaucitron\", /* Open the panel with this hashtag */
\t\t\t\t\"cookieName\": \"tarteaucitron\", /* Cookie name */
\t\t\t\t\"orientation\": \"middle\", /* Banner position (top - bottom) */
\t\t\t\t\"groupServices\": false, /* Group services by category */
\t\t\t\t\"showDetailsOnClick\": true, /* Click to expand the description */
\t\t\t\t\"serviceDefaultState\": \"wait\", /* Default state (true - wait - false) */
\t\t\t\t\"showAlertSmall\": false, /* Show the small banner on bottom right */
\t\t\t\t\"cookieslist\": false, /* Show the cookie list */
\t\t\t\t\"closePopup\": false, /* Show a close X on the banner */
\t\t\t\t\"showIcon\": true,
\t\t\t\t/* Show cookie icon to manage cookies */
\t\t\t\t\"iconSrc\": \"img/imageCookie.png\", /* Optionnal: URL or base64 encoded image */
\t\t\t\t\"iconPosition\": \"BottomLeft\", /* BottomRight, BottomLeft, TopRight and TopLeft */
\t\t\t\t\"adblocker\": false, /* Show a Warning if an adblocker is detected */
\t\t\t\t\"DenyAllCta\": true, /* Show the deny all button */
\t\t\t\t\"AcceptAllCta\": true, /* Show the accept all button when highPrivacy on */
\t\t\t\t\"highPrivacy\": true, /* HIGHLY RECOMMANDED Disable auto consent */
\t\t\t\t\"alwaysNeedConsent\": false, /* Ask the consent for \"Privacy by design\" services */
\t\t\t\t\"handleBrowserDNTRequest\": false, /* If Do Not Track == 1, disallow all */
\t\t\t\t\"removeCredit\": false, /* Remove credit link */
\t\t\t\t\"moreInfoLink\": true, /* Show more info link */
\t\t\t\t\"useExternalCss\": false, /* If false, the tarteaucitron.css file will be loaded */
\t\t\t\t\"useExternalJs\": false,
\t\t\t\t/* If false, the tarteaucitron.js file will be loaded */
\t\t\t\t// \"cookieDomain\": \".my-multisite-domaine.fr\", /* Shared cookie for multisite */
\t\t\t\t\"readmoreLink\": \"\", /* Change the default readmore link */
\t\t\t\t\"mandatory\": true, /* Show a message about mandatory cookies */
\t\t\t\t\"mandatoryCta\": true,
\t\t\t\t/* Show the disabled accept button when mandatory on */
\t\t\t\t// \"customCloserId\": \"\", /* Optional a11y: Custom element ID used to open the panel */
\t\t\t\t\"googleConsentMode\": true, /* Enable Google Consent Mode v2 for Google ads and GA4 */
\t\t\t\t\"partnersList\": false /* Show the number of partners on the popup/middle banner */
\t\t\t});
\t\t</script>
\t\t<script>
\t\t\tconst TMDB_API_KEY = \"";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("TMDB_CLE_KEY"), "html", null, true);
        yield "\";
\t\t</script>
\t\t<script src=\"js/searchSuggestions.js\"></script>
\t\t<script src=\"js/patientez.js\"></script>
\t\t<script src=\"js/animations.js\"></script>
\t\t<script>
\t\t\tdocument.addEventListener('DOMContentLoaded', () => {
\t\t\t\tpatienterBouton(boutonDecouvrir);     // Boutons Découvrir dans le carrousel page d'accueil
\t\t\t\tpatienterLien(lienALaUne);            // Découvrir plus d'œuvres
\t\t\t\tpatienterBouton(boutonDecouvrirPlus); // Découvrir plus d'oeuvre
\t\t\t\tpatienterLien(lienCarouselSuggSerie); // Suggestion série
\t\t\t\tpatienterLien(lienCarouselSuggFilm);  // Suggestion film
\t\t\t\tpatienterLien(lienCarouselWatchList); // Watchlist film
\t\t\t});
\t\t</script>
\t\t<!-- Google tag (gtag.js) -->
\t\t";
        // line 69
        yield "\t\t<script type=\"text/javascript\" src=\"https://platform-api.sharethis.com/js/sharethis.js#property=6761894fc433c60012502e7c&product=inline-share-buttons&source=platform\" async=\"async\"></script>
\t\t<meta charset=\"UTF-8\">
\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t\t<link rel=\"icon\" href=\"";
        // line 72
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("WEBSITE_LOGO"), "html", null, true);
        yield "\">
\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"node_modules/bootstrap/dist/css/bootstrap.min.css\">
\t\t<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css\">
\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">
\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
\t\t<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css\">
\t\t<meta name=\"description\" content=\"";
        // line 78
        if (array_key_exists("description", $context)) {
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["description"] ?? null), "html", null, true);
            yield " ";
        } else {
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("WEBSITE_DESCRIPTION"), "html", null, true);
            yield " ";
        }
        yield "\">
\t\t<style>
\t\t\t.bg-custom {
\t\t\t\tbackground-color: #D5D0AF;
\t\t\t}
\t\t\t.max-width-400 {
\t\t\t\tmax-width: 400px;
\t\t\t}
\t\t\t.result-item:hover {
\t\t\t\tbackground-color: #f0f0f0;
\t\t\t}
\t\t</style>
\t\t";
        // line 90
        yield from $this->unwrap()->yieldBlock('css', $context, $blocks);
        // line 91
        yield "\t\t<title>
\t\t\t";
        // line 92
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        // line 95
        yield "\t\t</title>
\t</head>
\t<body class=\"d-flex flex-column min-vh-100 bg-custom\">
\t\t<div id=\"globalLoader\" class=\"d-none\">
\t\t\t<div class=\"loader-content d-inline-flex flex-column align-items-center p-3 bg-dark text-white rounded shadow-lg position-fixed top-50 start-50 translate-middle\" style=\"z-index: 9999;\">
\t\t\t  <div class=\"d-flex align-items-center\">
\t\t\t\t<img src=\"img/LogoPopCorn.png\" alt=\"Logo\" style=\"max-width: 80px;\">
\t\t\t\t<div class=\"popcorn-animation d-flex align-items-center ms-3\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t</div>
\t\t\t  </div>
\t\t\t  <div class=\"mt-2 text-center\">Patientez SVP</div>
\t\t\t</div>
\t\t</div>
\t\t<header class=\"header-bg text-light header-sm container-fluid\">
\t\t\t<nav class=\"navbar container-fluid d-flex justify-content-between align-items-center flex-wrap\">
\t\t\t\t<!-- Logo et bouton Menu -->
\t\t\t\t<div class=\"d-flex align-items-center\">
\t\t\t\t\t<a class=\"navbar-brand d-flex align-items-center\" href=\"index.php\">
\t\t\t\t\t\t<img src=\"img/LogoPopCorn.png\" alt=\"Logo\" width=\"90\" height=\"70\" class=\"me-2\">
\t\t\t\t\t</a>
\t\t\t\t\t";
        // line 123
        yield "\t\t\t\t</div>
\t\t\t\t<div class=\"py-2 d-flex justify-content-center flex-wrap\">
\t\t\t\t\t";
        // line 125
        if (($context["utilisateurConnecte"] ?? null)) {
            // line 126
            yield "\t\t\t\t\t\t<a href=\"index.php?controleur=forum&methode=listerForum\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Forum</a>
\t\t\t\t\t\t<a href=\"index.php?controleur=watchlist&methode=listerWatchList&id=";
            // line 127
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "idUtilisateur", [], "any", false, false, false, 127), "html", null, true);
            yield "\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Mes listes</a>
\t\t\t\t\t\t<a href=\"index.php?controleur=Quizz&methode=listerQuizz\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Quiz</a>
\t\t\t\t\t\t<a href=\"index.php?controleur=jeux&methode=listeTableau\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Jeux</a>
\t\t\t\t\t";
        } else {
            // line 131
            yield "\t\t\t\t\t\t<a href=\"index.php?controleur=utilisateur&methode=connexion\" class=\"btn btn-custom mx-2 my-1\">Merci de vous connecter</a>
\t\t\t\t\t";
        }
        // line 133
        yield "\t\t\t\t</div>
\t\t\t\t<div class=\"my-2\"></div> <!-- Espacement ajouté ici -->
\t\t\t\t";
        // line 136
        yield "\t\t\t\t<form class=\" d-flex justify-content-center flex-grow-1 my-2 my-lg-0 mx-auto\" action=\"index.php\" method=\"GET\" style=\"position: relative; margin-right: 413px;\">
\t\t\t\t";
        // line 138
        yield "\t\t\t\t\t<input type=\"hidden\" name=\"controleur\" value=\"index\">
\t\t\t\t\t<input type=\"hidden\" name=\"methode\" value=\"rechercherFilm\">
\t\t\t\t\t<input class=\"form-control\" type=\"text\" name=\"requete\" placeholder=\"Rechercher un film ou une série \" aria-label=\"Search\" style=\"max-width: 400px;\">
\t\t\t\t</form>
\t\t\t\t<!-- Bouton Connexion/Déconnexion et Admin -->
\t\t\t\t<div class=\"me-3 mt-2 mt-md-0\">
\t\t\t\t\t";
        // line 144
        if ((null === ($context["utilisateurConnecte"] ?? null))) {
            // line 145
            yield "\t\t\t\t\t\t<a class=\"btn btn-custom\" href=\"index.php?controleur=utilisateur&methode=connexion\">Connexion</a>
\t\t\t\t\t";
        } else {
            // line 147
            yield "\t\t\t\t\t\t<div class=\"dropdown\">
\t\t\t\t\t\t\t<button class=\"btn d-flex align-items-center p-0 border-0\" type=\"button\" id=\"userMenuButton\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
\t\t\t\t\t\t\t\t<img src=\"";
            // line 149
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("img/profils/" . CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "photoProfil", [], "any", false, false, false, 149)), "html", null, true);
            yield "\" alt=\"Photo de profil\" width=\"50\" height=\"50\" class=\"rounded-circle border border-2\">
\t\t\t\t\t\t\t\t<i class=\"fas fa-caret-down ms-2 text-white\"></i>
\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t\t<ul class=\"dropdown-menu dropdown-menu-end shadow\" aria-labelledby=\"userMenuButton\">
\t\t\t\t\t\t\t\t<li class=\"dropdown-item\">
\t\t\t\t\t\t\t\t\t<a href=\"index.php?controleur=profil&methode=afficherApropos&id=";
            // line 154
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "idUtilisateur", [], "any", false, false, false, 154), "html", null, true);
            yield "\" class=\"d-flex align-items-center text-decoration-none\">
\t\t\t\t\t\t\t\t\t\t<img src=\"";
            // line 155
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("img/profils/" . CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "photoProfil", [], "any", false, false, false, 155)), "html", null, true);
            yield "\" alt=\"Photo de profil\" width=\"50\" height=\"50\" class=\"rounded-circle me-2\">
\t\t\t\t\t\t\t\t\t\t<div>
\t\t\t\t\t\t\t\t\t\t\t<strong>";
            // line 157
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "pseudo", [], "any", false, false, false, 157), "html", null, true);
            yield "</strong><br>
\t\t\t\t\t\t\t\t\t\t\t<small class=\"text-muted\">";
            // line 158
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "getRole", [], "any", false, false, false, 158), "html", null, true);
            yield "</small>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t\t<div class=\"mt-2\">
\t\t\t\t\t\t\t\t\t\t\t<small>Cliquez pour afficher votre profil</small>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<hr class=\"dropdown-divider\">
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t";
            // line 168
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["utilisateurConnecte"] ?? null), "getRole", [], "any", false, false, false, 168) == "admin")) {
                // line 169
                yield "\t\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item\" href=\"index.php?controleur=admin&methode=render\" style=\"color: #A2140A;\">
\t\t\t\t\t\t\t\t\t\t\t<strong>Administration</strong>
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t";
            }
            // line 175
            yield "\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item\" href=\"index.php?controleur=profil&methode=afficherFormulaire\" style=\"color: #A2140A;\">Paramètres</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item\" href=\"index.php?controleur=profil&methode=listerNotif&idNotif=1\" style=\"color: #A2140A;\">Mes notifications</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<hr class=\"dropdown-divider\">
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item text-danger fw-bold\" href=\"index.php?controleur=utilisateur&methode=deconnexion\">Déconnexion</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t</div>
\t\t\t\t\t";
        }
        // line 190
        yield "\t\t\t\t</div>
\t\t\t</nav>
\t\t</header>
\t\t<!-- Contenu principal avec flex-grow-1 pour prendre tout l'espace disponible -->
\t\t<main class=\"flex-grow-1\">
\t\t\t";
        // line 196
        yield "\t\t\t";
        yield from $this->unwrap()->yieldBlock('content1', $context, $blocks);
        // line 197
        yield "\t\t\t<script type=\"text/javascript\">
\t\t\t\ttarteaucitron.user.gtagUa = 'G-TJGCV8L5XE';
\t\t\t\t// tarteaucitron.user.gtagCrossdomain = ['example.com', 'example2.com'];
\t\t\t\ttarteaucitron.user.gtagMore = function () { /* add here your optionnal gtag() */ };
\t\t\t\t(tarteaucitron.job = tarteaucitron.job || []).push('gtag');
\t\t\t</script>
\t\t\t<script type=\"text/javascript\">
\t\t\t\ttarteaucitron.user.sharethisPublisher = 'publisher';
\t\t\t\t(tarteaucitron.job = tarteaucitron.job || []).push('sharethis');
\t\t\t</script>
\t\t\t";
        // line 208
        yield "\t\t\t";
        yield from $this->unwrap()->yieldBlock('content2', $context, $blocks);
        // line 209
        yield "\t\t\t";
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 210
        yield "\t\t</main>
\t\t<footer class=\"footer-bg text-center text-light py-3 mt-auto\">
\t\t\t<div class=\"container\">
\t\t\t\t<!-- Liens de Navigation -->
\t\t\t\t<div class=\"footer-nav mb-3\">
\t\t\t\t\t<a href=\"index.php\" class=\"footer-link mx-2\">Accueil</a>
\t\t\t\t\t<a href=\"#\" class=\"footer-link mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#aproposModal\">À propos</a>
\t\t\t\t\t<a href=\"#\" class=\"footer-link mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#contactModal\">Contact</a>
\t\t\t\t\t<a href=\"index.php?controleur=newsletter&methode=afficher\" class=\"footer-link mx-2\">Newletter</a>
\t\t\t\t\t<a href=\"#\" class=\"footer-link mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#tmdbModal\">
\t\t\t\t\t\t<img src=\"img/logoTMDB.png\" alt=\"TMDB Logo\" width=\"20\" height=\"20\" class=\"me-1\">CGU
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t\t<!-- PopUp pour l'accord TMDB -->
\t\t\t\t<div class=\"modal fade\" id=\"tmdbModal\" tabindex=\"-1\" aria-labelledby=\"tmdbModalLabel\" aria-hidden=\"true\">
\t\t\t\t\t<div class=\"modal-dialog\">
\t\t\t\t\t\t<div class=\"modal-content\">
\t\t\t\t\t\t\t<div class=\"modal-header bg-light\">
\t\t\t\t\t\t\t\t<img src=\"img/logoTMDB.png\" alt=\"TMDB Logo\" width=\"50\" height=\"50\" class=\"me-3\">
\t\t\t\t\t\t\t\t<h4 class=\"modal-title fw-bold text-dark\" id=\"tmdbModalLabel\">Accord d'utilisation TMDB</h4>
\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Fermer\"></button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"modal-body text-dark\">
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tThe Movie Database (TMDB) est une base de données en ligne regroupant des milliers de données sur des oeuvres audiovisuelles. L'accès aux informations est publiques et gratuite sous certaines conditions grâce à une API.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tL'utilisation de TMDB est soumise à des conditions d'utilisation spécifiques. En utilisant les données de TMDB, nous acceptons de respecter leurs conditions d'utilisation, disponibles sur leur site officiel.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tPour plus de détails, voici un lien vers les
\t\t\t\t\t\t\t\t\t<a href=\"https://www.themoviedb.org/terms-of-use\" target=\"_blank\" class=\"text-primary fw-bold\">Conditions d'utilisation de TMDB</a>.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t<h5>Utilisation de TMDB dans notre projet</h5>
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tNos données des œuvres sont récupérées grâce à l’API de TMDB. L'API est limitée dans le nombre de requêtes quotidiennes (100 000, ce qui est largement assez pour notre usage). Nous avons le droit de l’utiliser dans un cadre non commercial (notre application ne générant pas de revenu, nous sommes dans la légalité), et il faut absolument créditer TMDB sur notre projet (ce qui est fait aussi). TMDB permet d’utiliser les affiches des œuvres dans un cadre strictement non commercial grâce à un accord implicite entre TMDB et les détenteurs des droits des affiches. Les seules conditions sont de bien mentionner TMDB, et de ne pas altérer les affiches.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<!-- Modal À propos -->
\t\t\t\t<div class=\"modal fade\" id=\"aproposModal\" tabindex=\"-1\" aria-labelledby=\"aproposModalLabel\" aria-hidden=\"true\">
\t\t\t\t\t<div class=\"modal-dialog modal-dialog-centered\">
\t\t\t\t\t\t<div class=\"modal-content\">
\t\t\t\t\t\t\t<div class=\"modal-header bg-light\">
\t\t\t\t\t\t\t\t<h5 class=\"modal-title fw-bold text-dark\" id=\"aproposModalLabel\">À propos</h5>
\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Fermer\"></button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"modal-body text-dark\">
\t\t\t\t\t\t\t\t<p>Bienvenue sur
\t\t\t\t\t\t\t\t\t<strong>VHS</strong>, votre plateforme dédiée aux œuvres audiovisuelles.</p>
\t\t\t\t\t\t\t\t<p>Ce projet a été réalisé dans le cadre du BUT Informatique à l'IUT de Bayonne et du Pays Basque.</p>
\t\t\t\t\t\t\t\t<p>Nous utilisons l'API de
\t\t\t\t\t\t\t\t\t<a href=\"https://www.themoviedb.org/\" target=\"_blank\" class=\"text-primary fw-bold\">TMDB</a>
\t\t\t\t\t\t\t\t\tpour enrichir notre contenu.</p>
\t\t\t\t\t\t\t\t<h6>Crédits :</h6>
\t\t\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t\t\t<li>AMREIN Nathan - TP4</li>
\t\t\t\t\t\t\t\t\t<li>BAROS Arthur - TP3</li>
\t\t\t\t\t\t\t\t\t<li>CHIPY Thibault - TP4</li>
\t\t\t\t\t\t\t\t\t<li>DESPRE-HILDEVERT Léa - TP4</li>
\t\t\t\t\t\t\t\t\t<li>DIZY Lukas - TP3 (alternant)</li>
\t\t\t\t\t\t\t\t\t<li>LEVAL Noah - TP4</li>
\t\t\t\t\t\t\t\t\t<li>NOVION Tatiana - TP4 (alternante)</li>
\t\t\t\t\t\t\t\t\t<li>PIGEON Aymeric - TP 4</li>
\t\t\t\t\t\t\t\t\t<li>VINET LATRILLE Jules - TP4</li>
\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t\t<p>Pour toute question, contactez-nous via la section
\t\t\t\t\t\t\t\t\t<strong>Contact</strong>.</p>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<!-- Modal Contact -->
\t\t\t\t<div class=\"modal fade\" id=\"contactModal\" tabindex=\"-1\" aria-labelledby=\"contactModalLabel\" aria-hidden=\"true\">
\t\t\t\t\t<div class=\"modal-dialog modal-dialog-centered\">
\t\t\t\t\t\t<div class=\"modal-content\">
\t\t\t\t\t\t\t<div class=\"modal-header bg-light\">
\t\t\t\t\t\t\t\t<h5 class=\"modal-title fw-bold text-dark\" id=\"contactModalLabel\">Nous contacter</h5>
\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Fermer\"></button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<form action=\"index.php?controleur=utilisateur&methode=traiterContact\" method=\"POST\">
\t\t\t\t\t\t\t\t<div class=\"modal-body text-dark\">
\t\t\t\t\t\t\t\t\t<div class=\"mb-3\">
\t\t\t\t\t\t\t\t\t\t<label for=\"name\" class=\"form-label\">Nom :</label>
\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"name\" name=\"name\" class=\"form-control\" required>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"form-group mb-3\">
\t\t\t\t\t\t\t\t\t\t<label for=\"email\" class=\"form-label\">Mail :</label>
\t\t\t\t\t\t\t\t\t\t<input type=\"email\" name=\"mail\" class=\"form-control\" required placeholder=\"Exemple : mail@mail.fr\">
\t\t\t\t\t\t\t\t\t\t<small id=\"emailErreur\" class=\"text-danger\"></small>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"mb-3\">
\t\t\t\t\t\t\t\t\t\t<label for=\"message\" class=\"form-label\">Message :</label>
\t\t\t\t\t\t\t\t\t\t<textarea id=\"message\" name=\"message\" class=\"form-control\" rows=\"5\" required></textarea>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"modal-footer\">
\t\t\t\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-primary w-100\" onclick=\"showPopup()\">Envoyer</button>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</form>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"footer-social mb-2\">
\t\t\t\t\t<div class=\"sharethis-inline-share-buttons\"></div>
\t\t\t\t</div>
\t\t\t\t<span class=\"tacSharethis\"></span>
\t\t\t\t<div class=\"footer-copyright\">
\t\t\t\t\t© Copyright 2024 - IUT de Bayonne et du Pays Basque - BUT2 Groupe 8
\t\t\t\t</div>
\t\t\t</div>
\t\t</footer>
\t\t";
        // line 324
        yield from $this->unwrap()->yieldBlock('scripts', $context, $blocks);
        // line 325
        yield "\t</body>
</html>
";
        yield from [];
    }

    // line 90
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_css(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 92
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 93
        yield "\t\t\t\t";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("WEBSITE_TITLE"), "html", null, true);
        yield "
\t\t\t";
        yield from [];
    }

    // line 196
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content1(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 208
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content2(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 209
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 324
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_scripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "base_template.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  502 => 324,  492 => 209,  482 => 208,  472 => 196,  464 => 93,  457 => 92,  447 => 90,  440 => 325,  438 => 324,  322 => 210,  319 => 209,  316 => 208,  304 => 197,  301 => 196,  294 => 190,  277 => 175,  269 => 169,  267 => 168,  254 => 158,  250 => 157,  245 => 155,  241 => 154,  233 => 149,  229 => 147,  225 => 145,  223 => 144,  215 => 138,  212 => 136,  208 => 133,  204 => 131,  197 => 127,  194 => 126,  192 => 125,  188 => 123,  161 => 95,  159 => 92,  156 => 91,  154 => 90,  131 => 78,  122 => 72,  117 => 69,  98 => 46,  51 => 2,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"{{ constant('WEBSITE_LANGUAGE') }}\">
\t<head>
\t\t<!-- Bootstrap JS -->
\t\t<script src=\"node_modules/tarteaucitronjs/tarteaucitron.js\"></script>
\t\t<script src=\"node_modules/bootstrap/dist/js/bootstrap.bundle.js\"></script>
\t\t<script type=\"text/javascript\">
\t\t\ttarteaucitron.init({
\t\t\t\t\"privacyUrl\": \"\", /* Privacy policy url */
\t\t\t\t\"bodyPosition\": \"bottom\", /* or top to bring it as first element for accessibility */
\t\t\t\t\"hashtag\": \"#tarteaucitron\", /* Open the panel with this hashtag */
\t\t\t\t\"cookieName\": \"tarteaucitron\", /* Cookie name */
\t\t\t\t\"orientation\": \"middle\", /* Banner position (top - bottom) */
\t\t\t\t\"groupServices\": false, /* Group services by category */
\t\t\t\t\"showDetailsOnClick\": true, /* Click to expand the description */
\t\t\t\t\"serviceDefaultState\": \"wait\", /* Default state (true - wait - false) */
\t\t\t\t\"showAlertSmall\": false, /* Show the small banner on bottom right */
\t\t\t\t\"cookieslist\": false, /* Show the cookie list */
\t\t\t\t\"closePopup\": false, /* Show a close X on the banner */
\t\t\t\t\"showIcon\": true,
\t\t\t\t/* Show cookie icon to manage cookies */
\t\t\t\t\"iconSrc\": \"img/imageCookie.png\", /* Optionnal: URL or base64 encoded image */
\t\t\t\t\"iconPosition\": \"BottomLeft\", /* BottomRight, BottomLeft, TopRight and TopLeft */
\t\t\t\t\"adblocker\": false, /* Show a Warning if an adblocker is detected */
\t\t\t\t\"DenyAllCta\": true, /* Show the deny all button */
\t\t\t\t\"AcceptAllCta\": true, /* Show the accept all button when highPrivacy on */
\t\t\t\t\"highPrivacy\": true, /* HIGHLY RECOMMANDED Disable auto consent */
\t\t\t\t\"alwaysNeedConsent\": false, /* Ask the consent for \"Privacy by design\" services */
\t\t\t\t\"handleBrowserDNTRequest\": false, /* If Do Not Track == 1, disallow all */
\t\t\t\t\"removeCredit\": false, /* Remove credit link */
\t\t\t\t\"moreInfoLink\": true, /* Show more info link */
\t\t\t\t\"useExternalCss\": false, /* If false, the tarteaucitron.css file will be loaded */
\t\t\t\t\"useExternalJs\": false,
\t\t\t\t/* If false, the tarteaucitron.js file will be loaded */
\t\t\t\t// \"cookieDomain\": \".my-multisite-domaine.fr\", /* Shared cookie for multisite */
\t\t\t\t\"readmoreLink\": \"\", /* Change the default readmore link */
\t\t\t\t\"mandatory\": true, /* Show a message about mandatory cookies */
\t\t\t\t\"mandatoryCta\": true,
\t\t\t\t/* Show the disabled accept button when mandatory on */
\t\t\t\t// \"customCloserId\": \"\", /* Optional a11y: Custom element ID used to open the panel */
\t\t\t\t\"googleConsentMode\": true, /* Enable Google Consent Mode v2 for Google ads and GA4 */
\t\t\t\t\"partnersList\": false /* Show the number of partners on the popup/middle banner */
\t\t\t});
\t\t</script>
\t\t<script>
\t\t\tconst TMDB_API_KEY = \"{{ constant('TMDB_CLE_KEY') }}\";
\t\t</script>
\t\t<script src=\"js/searchSuggestions.js\"></script>
\t\t<script src=\"js/patientez.js\"></script>
\t\t<script src=\"js/animations.js\"></script>
\t\t<script>
\t\t\tdocument.addEventListener('DOMContentLoaded', () => {
\t\t\t\tpatienterBouton(boutonDecouvrir);     // Boutons Découvrir dans le carrousel page d'accueil
\t\t\t\tpatienterLien(lienALaUne);            // Découvrir plus d'œuvres
\t\t\t\tpatienterBouton(boutonDecouvrirPlus); // Découvrir plus d'oeuvre
\t\t\t\tpatienterLien(lienCarouselSuggSerie); // Suggestion série
\t\t\t\tpatienterLien(lienCarouselSuggFilm);  // Suggestion film
\t\t\t\tpatienterLien(lienCarouselWatchList); // Watchlist film
\t\t\t});
\t\t</script>
\t\t<!-- Google tag (gtag.js) -->
\t\t{# <script async src=\"https://www.googletagmanager.com/gtag/js?id=G-TJGCV8L5XE\"></script>
\t\t\t<script>
\t\t\t\twindow.dataLayer = window.dataLayer || [];
\t\t\t\tfunction gtag(){dataLayer.push(arguments);}
\t\t\t\tgtag('js', new Date());
\t\t\t\tgtag('config', 'G-TJGCV8L5XE');
\t\t\t</script> #}
\t\t<script type=\"text/javascript\" src=\"https://platform-api.sharethis.com/js/sharethis.js#property=6761894fc433c60012502e7c&product=inline-share-buttons&source=platform\" async=\"async\"></script>
\t\t<meta charset=\"UTF-8\">
\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t\t<link rel=\"icon\" href=\"{{ constant('WEBSITE_LOGO') }}\">
\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"node_modules/bootstrap/dist/css/bootstrap.min.css\">
\t\t<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css\">
\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">
\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
\t\t<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css\">
\t\t<meta name=\"description\" content=\"{% if description is defined %} {{ description }} {% else %} {{ constant('WEBSITE_DESCRIPTION') }} {% endif %}\">
\t\t<style>
\t\t\t.bg-custom {
\t\t\t\tbackground-color: #D5D0AF;
\t\t\t}
\t\t\t.max-width-400 {
\t\t\t\tmax-width: 400px;
\t\t\t}
\t\t\t.result-item:hover {
\t\t\t\tbackground-color: #f0f0f0;
\t\t\t}
\t\t</style>
\t\t{% block css %}{% endblock %}
\t\t<title>
\t\t\t{% block title %}
\t\t\t\t{{ constant('WEBSITE_TITLE') }}
\t\t\t{% endblock %}
\t\t</title>
\t</head>
\t<body class=\"d-flex flex-column min-vh-100 bg-custom\">
\t\t<div id=\"globalLoader\" class=\"d-none\">
\t\t\t<div class=\"loader-content d-inline-flex flex-column align-items-center p-3 bg-dark text-white rounded shadow-lg position-fixed top-50 start-50 translate-middle\" style=\"z-index: 9999;\">
\t\t\t  <div class=\"d-flex align-items-center\">
\t\t\t\t<img src=\"img/LogoPopCorn.png\" alt=\"Logo\" style=\"max-width: 80px;\">
\t\t\t\t<div class=\"popcorn-animation d-flex align-items-center ms-3\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t  <img src=\"img/noteVide.png\" class=\"popcorn mx-1\" alt=\"PopCorn\" style=\"max-width: 20px;\">
\t\t\t\t</div>
\t\t\t  </div>
\t\t\t  <div class=\"mt-2 text-center\">Patientez SVP</div>
\t\t\t</div>
\t\t</div>
\t\t<header class=\"header-bg text-light header-sm container-fluid\">
\t\t\t<nav class=\"navbar container-fluid d-flex justify-content-between align-items-center flex-wrap\">
\t\t\t\t<!-- Logo et bouton Menu -->
\t\t\t\t<div class=\"d-flex align-items-center\">
\t\t\t\t\t<a class=\"navbar-brand d-flex align-items-center\" href=\"index.php\">
\t\t\t\t\t\t<img src=\"img/LogoPopCorn.png\" alt=\"Logo\" width=\"90\" height=\"70\" class=\"me-2\">
\t\t\t\t\t</a>
\t\t\t\t\t{# <button class=\"btn btn-custom ms-2\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#menuBar\" aria-expanded=\"false\" aria-controls=\"menuBar\">
\t\t\t\t\t\tMenu
\t\t\t\t\t</button> #}
\t\t\t\t</div>
\t\t\t\t<div class=\"py-2 d-flex justify-content-center flex-wrap\">
\t\t\t\t\t{% if utilisateurConnecte %}
\t\t\t\t\t\t<a href=\"index.php?controleur=forum&methode=listerForum\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Forum</a>
\t\t\t\t\t\t<a href=\"index.php?controleur=watchlist&methode=listerWatchList&id={{ utilisateurConnecte.idUtilisateur }}\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Mes listes</a>
\t\t\t\t\t\t<a href=\"index.php?controleur=Quizz&methode=listerQuizz\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Quiz</a>
\t\t\t\t\t\t<a href=\"index.php?controleur=jeux&methode=listeTableau\" class=\"btn btn-light-custom mx-2 my-1\" style=\"font-weight: bold\">Jeux</a>
\t\t\t\t\t{% else %}
\t\t\t\t\t\t<a href=\"index.php?controleur=utilisateur&methode=connexion\" class=\"btn btn-custom mx-2 my-1\">Merci de vous connecter</a>
\t\t\t\t\t{% endif %}
\t\t\t\t</div>
\t\t\t\t<div class=\"my-2\"></div> <!-- Espacement ajouté ici -->
\t\t\t\t{# <!-- Barre de recherche -->d-flex justify-content-center flex-grow-1 mt-2 mt-md-0 #}
\t\t\t\t<form class=\" d-flex justify-content-center flex-grow-1 my-2 my-lg-0 mx-auto\" action=\"index.php\" method=\"GET\" style=\"position: relative; margin-right: 413px;\">
\t\t\t\t{# <form class=\" d-flex justify-content-center flex-grow-1 mt-2 mt-md-0\" action=\"index.php?controleur=index&methode=rechercherFilm\" method=\"POST\"  style=\"position: relative; margin-right: 413px;\"> #}
\t\t\t\t\t<input type=\"hidden\" name=\"controleur\" value=\"index\">
\t\t\t\t\t<input type=\"hidden\" name=\"methode\" value=\"rechercherFilm\">
\t\t\t\t\t<input class=\"form-control\" type=\"text\" name=\"requete\" placeholder=\"Rechercher un film ou une série \" aria-label=\"Search\" style=\"max-width: 400px;\">
\t\t\t\t</form>
\t\t\t\t<!-- Bouton Connexion/Déconnexion et Admin -->
\t\t\t\t<div class=\"me-3 mt-2 mt-md-0\">
\t\t\t\t\t{% if utilisateurConnecte is null %}
\t\t\t\t\t\t<a class=\"btn btn-custom\" href=\"index.php?controleur=utilisateur&methode=connexion\">Connexion</a>
\t\t\t\t\t{% else %}
\t\t\t\t\t\t<div class=\"dropdown\">
\t\t\t\t\t\t\t<button class=\"btn d-flex align-items-center p-0 border-0\" type=\"button\" id=\"userMenuButton\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
\t\t\t\t\t\t\t\t<img src=\"{{ 'img/profils/' ~ utilisateurConnecte.photoProfil }}\" alt=\"Photo de profil\" width=\"50\" height=\"50\" class=\"rounded-circle border border-2\">
\t\t\t\t\t\t\t\t<i class=\"fas fa-caret-down ms-2 text-white\"></i>
\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t\t<ul class=\"dropdown-menu dropdown-menu-end shadow\" aria-labelledby=\"userMenuButton\">
\t\t\t\t\t\t\t\t<li class=\"dropdown-item\">
\t\t\t\t\t\t\t\t\t<a href=\"index.php?controleur=profil&methode=afficherApropos&id={{ utilisateurConnecte.idUtilisateur }}\" class=\"d-flex align-items-center text-decoration-none\">
\t\t\t\t\t\t\t\t\t\t<img src=\"{{ 'img/profils/' ~ utilisateurConnecte.photoProfil }}\" alt=\"Photo de profil\" width=\"50\" height=\"50\" class=\"rounded-circle me-2\">
\t\t\t\t\t\t\t\t\t\t<div>
\t\t\t\t\t\t\t\t\t\t\t<strong>{{ utilisateurConnecte.pseudo }}</strong><br>
\t\t\t\t\t\t\t\t\t\t\t<small class=\"text-muted\">{{ utilisateurConnecte.getRole }}</small>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t\t<div class=\"mt-2\">
\t\t\t\t\t\t\t\t\t\t\t<small>Cliquez pour afficher votre profil</small>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<hr class=\"dropdown-divider\">
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t{% if utilisateurConnecte.getRole == 'admin' %}
\t\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item\" href=\"index.php?controleur=admin&methode=render\" style=\"color: #A2140A;\">
\t\t\t\t\t\t\t\t\t\t\t<strong>Administration</strong>
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t{% endif %}
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item\" href=\"index.php?controleur=profil&methode=afficherFormulaire\" style=\"color: #A2140A;\">Paramètres</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item\" href=\"index.php?controleur=profil&methode=listerNotif&idNotif=1\" style=\"color: #A2140A;\">Mes notifications</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<hr class=\"dropdown-divider\">
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t\t<li>
\t\t\t\t\t\t\t\t\t<a class=\"dropdown-item text-danger fw-bold\" href=\"index.php?controleur=utilisateur&methode=deconnexion\">Déconnexion</a>
\t\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t</div>
\t\t\t\t\t{% endif %}
\t\t\t\t</div>
\t\t\t</nav>
\t\t</header>
\t\t<!-- Contenu principal avec flex-grow-1 pour prendre tout l'espace disponible -->
\t\t<main class=\"flex-grow-1\">
\t\t\t{# affiche le base_template_film #}
\t\t\t{% block content1 %}{% endblock %}
\t\t\t<script type=\"text/javascript\">
\t\t\t\ttarteaucitron.user.gtagUa = 'G-TJGCV8L5XE';
\t\t\t\t// tarteaucitron.user.gtagCrossdomain = ['example.com', 'example2.com'];
\t\t\t\ttarteaucitron.user.gtagMore = function () { /* add here your optionnal gtag() */ };
\t\t\t\t(tarteaucitron.job = tarteaucitron.job || []).push('gtag');
\t\t\t</script>
\t\t\t<script type=\"text/javascript\">
\t\t\t\ttarteaucitron.user.sharethisPublisher = 'publisher';
\t\t\t\t(tarteaucitron.job = tarteaucitron.job || []).push('sharethis');
\t\t\t</script>
\t\t\t{# affiche le base_template_profil #}
\t\t\t{% block content2 %}{% endblock %}
\t\t\t{% block content %}{% endblock %}
\t\t</main>
\t\t<footer class=\"footer-bg text-center text-light py-3 mt-auto\">
\t\t\t<div class=\"container\">
\t\t\t\t<!-- Liens de Navigation -->
\t\t\t\t<div class=\"footer-nav mb-3\">
\t\t\t\t\t<a href=\"index.php\" class=\"footer-link mx-2\">Accueil</a>
\t\t\t\t\t<a href=\"#\" class=\"footer-link mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#aproposModal\">À propos</a>
\t\t\t\t\t<a href=\"#\" class=\"footer-link mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#contactModal\">Contact</a>
\t\t\t\t\t<a href=\"index.php?controleur=newsletter&methode=afficher\" class=\"footer-link mx-2\">Newletter</a>
\t\t\t\t\t<a href=\"#\" class=\"footer-link mx-2\" data-bs-toggle=\"modal\" data-bs-target=\"#tmdbModal\">
\t\t\t\t\t\t<img src=\"img/logoTMDB.png\" alt=\"TMDB Logo\" width=\"20\" height=\"20\" class=\"me-1\">CGU
\t\t\t\t\t</a>
\t\t\t\t</div>
\t\t\t\t<!-- PopUp pour l'accord TMDB -->
\t\t\t\t<div class=\"modal fade\" id=\"tmdbModal\" tabindex=\"-1\" aria-labelledby=\"tmdbModalLabel\" aria-hidden=\"true\">
\t\t\t\t\t<div class=\"modal-dialog\">
\t\t\t\t\t\t<div class=\"modal-content\">
\t\t\t\t\t\t\t<div class=\"modal-header bg-light\">
\t\t\t\t\t\t\t\t<img src=\"img/logoTMDB.png\" alt=\"TMDB Logo\" width=\"50\" height=\"50\" class=\"me-3\">
\t\t\t\t\t\t\t\t<h4 class=\"modal-title fw-bold text-dark\" id=\"tmdbModalLabel\">Accord d'utilisation TMDB</h4>
\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Fermer\"></button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"modal-body text-dark\">
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tThe Movie Database (TMDB) est une base de données en ligne regroupant des milliers de données sur des oeuvres audiovisuelles. L'accès aux informations est publiques et gratuite sous certaines conditions grâce à une API.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tL'utilisation de TMDB est soumise à des conditions d'utilisation spécifiques. En utilisant les données de TMDB, nous acceptons de respecter leurs conditions d'utilisation, disponibles sur leur site officiel.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tPour plus de détails, voici un lien vers les
\t\t\t\t\t\t\t\t\t<a href=\"https://www.themoviedb.org/terms-of-use\" target=\"_blank\" class=\"text-primary fw-bold\">Conditions d'utilisation de TMDB</a>.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t\t<h5>Utilisation de TMDB dans notre projet</h5>
\t\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t\tNos données des œuvres sont récupérées grâce à l’API de TMDB. L'API est limitée dans le nombre de requêtes quotidiennes (100 000, ce qui est largement assez pour notre usage). Nous avons le droit de l’utiliser dans un cadre non commercial (notre application ne générant pas de revenu, nous sommes dans la légalité), et il faut absolument créditer TMDB sur notre projet (ce qui est fait aussi). TMDB permet d’utiliser les affiches des œuvres dans un cadre strictement non commercial grâce à un accord implicite entre TMDB et les détenteurs des droits des affiches. Les seules conditions sont de bien mentionner TMDB, et de ne pas altérer les affiches.
\t\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<!-- Modal À propos -->
\t\t\t\t<div class=\"modal fade\" id=\"aproposModal\" tabindex=\"-1\" aria-labelledby=\"aproposModalLabel\" aria-hidden=\"true\">
\t\t\t\t\t<div class=\"modal-dialog modal-dialog-centered\">
\t\t\t\t\t\t<div class=\"modal-content\">
\t\t\t\t\t\t\t<div class=\"modal-header bg-light\">
\t\t\t\t\t\t\t\t<h5 class=\"modal-title fw-bold text-dark\" id=\"aproposModalLabel\">À propos</h5>
\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Fermer\"></button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"modal-body text-dark\">
\t\t\t\t\t\t\t\t<p>Bienvenue sur
\t\t\t\t\t\t\t\t\t<strong>VHS</strong>, votre plateforme dédiée aux œuvres audiovisuelles.</p>
\t\t\t\t\t\t\t\t<p>Ce projet a été réalisé dans le cadre du BUT Informatique à l'IUT de Bayonne et du Pays Basque.</p>
\t\t\t\t\t\t\t\t<p>Nous utilisons l'API de
\t\t\t\t\t\t\t\t\t<a href=\"https://www.themoviedb.org/\" target=\"_blank\" class=\"text-primary fw-bold\">TMDB</a>
\t\t\t\t\t\t\t\t\tpour enrichir notre contenu.</p>
\t\t\t\t\t\t\t\t<h6>Crédits :</h6>
\t\t\t\t\t\t\t\t<ul>
\t\t\t\t\t\t\t\t\t<li>AMREIN Nathan - TP4</li>
\t\t\t\t\t\t\t\t\t<li>BAROS Arthur - TP3</li>
\t\t\t\t\t\t\t\t\t<li>CHIPY Thibault - TP4</li>
\t\t\t\t\t\t\t\t\t<li>DESPRE-HILDEVERT Léa - TP4</li>
\t\t\t\t\t\t\t\t\t<li>DIZY Lukas - TP3 (alternant)</li>
\t\t\t\t\t\t\t\t\t<li>LEVAL Noah - TP4</li>
\t\t\t\t\t\t\t\t\t<li>NOVION Tatiana - TP4 (alternante)</li>
\t\t\t\t\t\t\t\t\t<li>PIGEON Aymeric - TP 4</li>
\t\t\t\t\t\t\t\t\t<li>VINET LATRILLE Jules - TP4</li>
\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t\t<p>Pour toute question, contactez-nous via la section
\t\t\t\t\t\t\t\t\t<strong>Contact</strong>.</p>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<!-- Modal Contact -->
\t\t\t\t<div class=\"modal fade\" id=\"contactModal\" tabindex=\"-1\" aria-labelledby=\"contactModalLabel\" aria-hidden=\"true\">
\t\t\t\t\t<div class=\"modal-dialog modal-dialog-centered\">
\t\t\t\t\t\t<div class=\"modal-content\">
\t\t\t\t\t\t\t<div class=\"modal-header bg-light\">
\t\t\t\t\t\t\t\t<h5 class=\"modal-title fw-bold text-dark\" id=\"contactModalLabel\">Nous contacter</h5>
\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Fermer\"></button>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<form action=\"index.php?controleur=utilisateur&methode=traiterContact\" method=\"POST\">
\t\t\t\t\t\t\t\t<div class=\"modal-body text-dark\">
\t\t\t\t\t\t\t\t\t<div class=\"mb-3\">
\t\t\t\t\t\t\t\t\t\t<label for=\"name\" class=\"form-label\">Nom :</label>
\t\t\t\t\t\t\t\t\t\t<input type=\"text\" id=\"name\" name=\"name\" class=\"form-control\" required>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"form-group mb-3\">
\t\t\t\t\t\t\t\t\t\t<label for=\"email\" class=\"form-label\">Mail :</label>
\t\t\t\t\t\t\t\t\t\t<input type=\"email\" name=\"mail\" class=\"form-control\" required placeholder=\"Exemple : mail@mail.fr\">
\t\t\t\t\t\t\t\t\t\t<small id=\"emailErreur\" class=\"text-danger\"></small>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"mb-3\">
\t\t\t\t\t\t\t\t\t\t<label for=\"message\" class=\"form-label\">Message :</label>
\t\t\t\t\t\t\t\t\t\t<textarea id=\"message\" name=\"message\" class=\"form-control\" rows=\"5\" required></textarea>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"modal-footer\">
\t\t\t\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-primary w-100\" onclick=\"showPopup()\">Envoyer</button>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</form>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"footer-social mb-2\">
\t\t\t\t\t<div class=\"sharethis-inline-share-buttons\"></div>
\t\t\t\t</div>
\t\t\t\t<span class=\"tacSharethis\"></span>
\t\t\t\t<div class=\"footer-copyright\">
\t\t\t\t\t© Copyright 2024 - IUT de Bayonne et du Pays Basque - BUT2 Groupe 8
\t\t\t\t</div>
\t\t\t</div>
\t\t</footer>
\t\t{% block scripts %}{% endblock %}
\t</body>
</html>
", "base_template.html.twig", "/opt/lampp/htdocs/Video-Home-Share/templates/base_template.html.twig");
    }
}
