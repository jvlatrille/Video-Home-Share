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

/* index.html.twig */
class __TwigTemplate_df2531d8a379d0e8af5c49ecf515c173 extends Template
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

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'scripts' => [$this, 'block_scripts'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base_template.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base_template.html.twig", "index.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 4
        yield "<title>Bienvenue sur ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("WEBSITE_TITLE"), "html", null, true);
        yield "</title>

";
        // line 6
        if (Twig\Extension\CoreExtension::testEmpty(($context["oaListe"] ?? null))) {
            // line 7
            yield "    <p class=\"text-center mt-5\">Aucun film disponible pour le moment.</p>
";
        } else {
            // line 9
            yield "    <h2 class=\"text-center mt-5\">Les films et les séries les mieux notés</h2>
    <div id=\"carouselExample\" class=\"carousel slide\" data-bs-ride=\"carousel\">
        <div class=\"carousel-indicators\">
            ";
            // line 12
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(range(0, (Twig\Extension\CoreExtension::length($this->env->getCharset(), Twig\Extension\CoreExtension::slice($this->env->getCharset(), ($context["oaListe"] ?? null), 0, 10)) - 1)));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 13
                yield "                <button type=\"button\" data-bs-target=\"#carouselExample\" data-bs-slide-to=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["i"], "html", null, true);
                yield "\" ";
                if (($context["i"] == 0)) {
                    yield " class=\"active\" ";
                }
                yield " aria-label=\"Slide ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["i"] + 1), "html", null, true);
                yield "\"></button>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['i'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            yield "        </div>

        <div class=\"carousel-inner\">
            ";
            // line 18
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::slice($this->env->getCharset(), ($context["oaListe"] ?? null), 0, 10));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["oa"]) {
                // line 19
                yield "                <div class=\"carousel-item ";
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 19) == 0)) {
                    yield "active";
                }
                yield "\">
                    <div class=\"container py-4\">
                        <div class=\"row align-items-center justify-content-center\">
                            <div class=\"col-md-4 text-center\">

                                ";
                // line 24
                if (CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "posterPath", [], "any", false, false, false, 24)) {
                    // line 25
                    yield "                                    <a href=\"index.php?controleur=oa&methode=afficher";
                    yield (((CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "type", [], "any", false, false, false, 25) == "Film")) ? ("Film") : ("Serie"));
                    yield "&idOa=";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "idOa", [], "any", false, false, false, 25), "html", null, true);
                    yield "\">
                                        <img src=\"";
                    // line 26
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "posterPath", [], "any", false, false, false, 26), "html", null, true);
                    yield "\" alt=\"Affiche de ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "nom", [], "any", false, false, false, 26), "html", null, true);
                    yield "\" class=\"img-fluid rounded shadow-sm\" style=\"max-height: 400px; object-fit: cover;\" loading=\"lazy\">
                                    </a>
\t\t\t\t\t\t\t\t";
                } else {
                    // line 28
                    yield "                                
                                    <p><em>Affiche non disponible</em></p>
                                ";
                }
                // line 31
                yield "                            </div>
                            <div class=\"col-md-6 text-center\">
                                <h2 class=\"text-uppercase text-dark fs-3 fw-bold\">";
                // line 33
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "nom", [], "any", false, false, false, 33), "html", null, true);
                yield "</h2>
                                <p><strong>Date de sortie :</strong> ";
                // line 34
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "dateSortie", [], "any", false, false, false, 34), "d/m/Y"), "html", null, true);
                yield "</p>
                                <p><strong>Description :</strong></p>
                                <p class=\"text-muted\">";
                // line 36
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "description", [], "any", false, false, false, 36), 0, 200), "html", null, true);
                yield "...</p>
                                <p><strong>Note :</strong>
                                    ";
                // line 38
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 39
                    yield "                                        <img src=\"";
                    yield ((($context["i"] <= (CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "note", [], "any", false, false, false, 39) / 2))) ? ("img/noteRemplie.png") : ("img/noteVide.png"));
                    yield "\" alt=\"Note\" width=\"30\" height=\"30\" loading=\"lazy\">
                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['i'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 41
                yield "                                </p>
                                ";
                // line 42
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "type", [], "any", false, false, false, 42) == "Film")) {
                    // line 43
                    yield "                                    <a href=\"index.php?controleur=oa&methode=afficherFilm&idOa=";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "idOa", [], "any", false, false, false, 43), "html", null, true);
                    yield "\" class=\"btn btn-primary mt-2\">Découvrir</a>
                                ";
                } else {
                    // line 45
                    yield "                                    <a href=\"index.php?controleur=oa&methode=afficherSerie&idOa=";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "idOa", [], "any", false, false, false, 45), "html", null, true);
                    yield "\" class=\"btn btn-primary mt-2\">Découvrir</a>
                                ";
                }
                // line 47
                yield "                            </div>
                        </div>
                    </div>
                </div>
            ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['oa'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 52
            yield "        </div>
        <br>

        <button class=\"carousel-control-prev\" type=\"button\" data-bs-target=\"#carouselExample\" data-bs-slide=\"prev\" >
            <span class=\"carousel-control-prev-icon\" style=\" width: 70px; height: 70px;\" aria-hidden=\"true\"></span>
            <span class=\"visually-hidden\">Précédent</span>
        </button>
        <button class=\"carousel-control-next\" type=\"button\" data-bs-target=\"#carouselExample\" data-bs-slide=\"next\">
            <span class=\"carousel-control-next-icon\" style=\" width: 70px; height: 70px;\" aria-hidden=\"true\"></span>
            <span class=\"visually-hidden\">Suivant</span>
        </button>
    </div>

    <h2 class=\"text-center mt-5\">À la une</h2>
    <div class=\"container mt-4\">

        <div class=\"row row-cols-1 row-cols-md-5 g-4\">
            ";
            // line 69
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::slice($this->env->getCharset(), ($context["oaListe"] ?? null), 10, 20));
            foreach ($context['_seq'] as $context["_key"] => $context["oa"]) {
                // line 70
                yield "                <div class=\"col\">
                ";
                // line 71
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "type", [], "any", false, false, false, 71) == "Film")) {
                    // line 72
                    yield "                    <a href=\"index.php?controleur=oa&methode=afficherFilm&idOa=";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "idOa", [], "any", false, false, false, 72), "html", null, true);
                    yield "\" class=\"text-decoration-none\">
                ";
                } else {
                    // line 74
                    yield "                    <a href=\"index.php?controleur=oa&methode=afficherSerie&idOa=";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "idOa", [], "any", false, false, false, 74), "html", null, true);
                    yield "\" class=\"text-decoration-none\">
                ";
                }
                // line 76
                yield "                        <div class=\"card h-100 shadow-sm\">
                            ";
                // line 77
                if (CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "posterPath", [], "any", false, false, false, 77)) {
                    // line 78
                    yield "                                <img src=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "posterPath", [], "any", false, false, false, 78), "html", null, true);
                    yield "\" class=\"card-img-top\" alt=\"Affiche de ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "nom", [], "any", false, false, false, 78), "html", null, true);
                    yield "\" style=\"height: 250px; object-fit: cover;\" loading=\"lazy\">
                            ";
                } else {
                    // line 80
                    yield "                                <div class=\"card-img-top text-center py-5 bg-light\" style=\"height: 250px;\">Affiche non disponible</div>
                            ";
                }
                // line 82
                yield "                            <div class=\"card-body text-center\">
                                <h6 class=\"card-title text-truncate\">";
                // line 83
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "nom", [], "any", false, false, false, 83), "html", null, true);
                yield "</h6>
                            </div>
                        </div>
                    </a>
                </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['oa'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 89
            yield "        </div>
    </div>

    <h2 class=\"text-center mt-5\">Messages les plus likés</h2>
    <div class=\"container mt-4\">
        <div class=\"row row-cols-1 row-cols-md-3 g-4\">
            ";
            // line 95
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["topMessages"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 96
                yield "                <div class=\"col\">
                    <div class=\"card shadow-sm h-100\">
                        <div class=\"card-body text-center\">
                            <a href=\"index.php?controleur=utilisateur&methode=afficherAutreUtilisateur&pseudo=";
                // line 99
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["message"], "pseudo", [], "any", false, false, false, 99), "html", null, true);
                yield "\">
                                <img src=\"";
                // line 100
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("img/profils/" . CoreExtension::getAttribute($this->env, $this->source, $context["message"], "photoProfil", [], "any", false, false, false, 100)), "html", null, true);
                yield "\" 
                                    alt=\"Photo de profil de ";
                // line 101
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["message"], "pseudo", [], "any", false, false, false, 101), "html", null, true);
                yield "\" 
                                    class=\"rounded-circle mb-3 border border-3\" 
                                    style=\"width: 80px; height: 80px; object-fit: cover;\" loading=\"lazy\">
                            </a>
                            <h5 class=\"text-primary fw-bold\">";
                // line 105
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["message"], "pseudo", [], "any", false, false, false, 105), "html", null, true);
                yield "</h5>
                            <p class=\"text-muted small mb-1\">Forum : <strong>";
                // line 106
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["message"], "forumNom", [], "any", false, false, false, 106), "html", null, true);
                yield "</strong></p>
                            <p class=\"text-center text-muted\" style=\"font-size: 0.9rem;\">
                                ";
                // line 108
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["message"], "contenu", [], "any", false, false, false, 108), 0, 100) . "..."), "html", null, true);
                yield "
                            </p>
                            <p class=\"text-danger small mb-0\">
                                <i class=\"fas fa-heart\"></i> ";
                // line 111
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["message"], "nbLike", [], "any", false, false, false, 111), "html", null, true);
                yield " likes
                            </p>
                            <a href=\"index.php?controleur=message&methode=listerMessage&idForum=";
                // line 113
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["message"], "idForum", [], "any", false, false, false, 113), "html", null, true);
                yield "\" 
                            class=\"btn btn-outline-primary btn-sm mt-3\">Voir le forum</a>
                        </div>
                    </div>
                </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 119
            yield "        </div>
    </div>



    <h2 class=\"text-center mt-5\">Découvrir plus d'œuvres</h2>
    <div id=\"decouvrirOaContainer\" class=\"container mt-4\">
        <div class=\"row row-cols-1 row-cols-md-5 g-4\">
            ";
            // line 127
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["oaRandomListe"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["oa"]) {
                // line 128
                yield "                <div class=\"col\">
                    <a href=\"index.php?controleur=oa&methode=afficherFilm&idOa=";
                // line 129
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "idOa", [], "any", false, false, false, 129), "html", null, true);
                yield "\" class=\"text-decoration-none\">
                        <div class=\"card h-100 shadow-sm\">
                            ";
                // line 131
                if (CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "posterPath", [], "any", false, false, false, 131)) {
                    // line 132
                    yield "                                <img src=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "posterPath", [], "any", false, false, false, 132), "html", null, true);
                    yield "\" class=\"card-img-top\" alt=\"Affiche de ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "nom", [], "any", false, false, false, 132), "html", null, true);
                    yield "\" style=\"height: 250px; object-fit: cover;\" loading=\"lazy\">
                            ";
                } else {
                    // line 134
                    yield "                                <div class=\"card-img-top text-center py-5 bg-light\" style=\"height: 250px;\">Affiche non disponible</div>
                            ";
                }
                // line 136
                yield "                            <div class=\"card-body text-center\">
                                <h6 class=\"card-title text-truncate\">";
                // line 137
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["oa"], "nom", [], "any", false, false, false, 137), "html", null, true);
                yield "</h6>
                            </div>
                        </div>
                    </a>
                </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['oa'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 143
            yield "        </div>
    </div>

    <div class=\"text-center mt-4\">
        <button id=\"loadRandomBtn\" class=\"btn btn-primary\">Découvrir toujours plus !</button>
    </div>
\t<br><br>
";
        }
        // line 151
        yield "
";
        yield from [];
    }

    // line 154
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_scripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 155
        yield "<script>
document.addEventListener('DOMContentLoaded', function () {
    const loadRandomBtn = document.getElementById('loadRandomBtn');

    if (loadRandomBtn) {
        loadRandomBtn.addEventListener('click', function () {
            // Adapte le chemin en fonction du dossier dans lequel se trouve ton projet :
            fetch('index.php?controleur=oa&methode=decouvrirPlus')
                .then(response => {
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);

                        // On cible le container puis la div .row
                        const containerEl = document.getElementById('decouvrirOaContainer');
                        if (!containerEl) {
                            console.error('Container #decouvrirOaContainer non trouvé.');
                            return;
                        }
                        const row = containerEl.querySelector('.row');

                        if (!Array.isArray(data) || data.length === 0 || data.error) {
                            console.error('Erreur dans les données :', data.error || 'Aucune œuvre récupérée.');
                            alert('Impossible de charger de nouvelles œuvres.');
                            return;
                        }

                        // Ajoute chaque œuvre dans le container
                        data.forEach(oa => {
                            row.innerHTML += `
                                <div class=\"col\">
                                    <a href=\"index.php?controleur=oa&methode=\${oa.type == 'Film' ? 'afficherFilm' : 'afficherSerie'}&idOa=\${oa.idOa}\" class=\"text-decoration-none\">
                                        <div class=\"card h-100 shadow-sm\">
                                            \${oa.posterPath ? `<img src=\"\${oa.posterPath}\" class=\"card-img-top\" alt=\"Affiche de \${oa.nom}\" style=\"height: 250px; object-fit: cover;\" loading=\"lazy\">`
                                            : `<div class=\"card-img-top text-center py-5 bg-light\" style=\"height: 250px;\">Affiche non disponible</div>`}
                                            <div class=\"card-body text-center\">
                                                <h6 class=\"card-title text-truncate\">\${oa.nom}</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        });
                    } catch(e) {
                        console.error('Erreur lors du parsing du JSON:', e);
                        alert('Erreur de connexion avec le serveur.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du fetch:', error);
                    alert('Erreur de connexion avec le serveur.');
                });
        });
    }
});
</script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "index.html.twig";
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
        return array (  426 => 155,  419 => 154,  413 => 151,  403 => 143,  391 => 137,  388 => 136,  384 => 134,  376 => 132,  374 => 131,  369 => 129,  366 => 128,  362 => 127,  352 => 119,  340 => 113,  335 => 111,  329 => 108,  324 => 106,  320 => 105,  313 => 101,  309 => 100,  305 => 99,  300 => 96,  296 => 95,  288 => 89,  276 => 83,  273 => 82,  269 => 80,  261 => 78,  259 => 77,  256 => 76,  250 => 74,  244 => 72,  242 => 71,  239 => 70,  235 => 69,  216 => 52,  198 => 47,  192 => 45,  186 => 43,  184 => 42,  181 => 41,  172 => 39,  168 => 38,  163 => 36,  158 => 34,  154 => 33,  150 => 31,  145 => 28,  137 => 26,  130 => 25,  128 => 24,  117 => 19,  100 => 18,  95 => 15,  80 => 13,  76 => 12,  71 => 9,  67 => 7,  65 => 6,  59 => 4,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_template.html.twig' %}

{% block content %}
<title>Bienvenue sur {{ constant(\"WEBSITE_TITLE\") }}</title>

{% if oaListe is empty %}
    <p class=\"text-center mt-5\">Aucun film disponible pour le moment.</p>
{% else %}
    <h2 class=\"text-center mt-5\">Les films et les séries les mieux notés</h2>
    <div id=\"carouselExample\" class=\"carousel slide\" data-bs-ride=\"carousel\">
        <div class=\"carousel-indicators\">
            {% for i in 0..(oaListe|slice(0, 10)|length - 1) %}
                <button type=\"button\" data-bs-target=\"#carouselExample\" data-bs-slide-to=\"{{ i }}\" {% if i == 0 %} class=\"active\" {% endif %} aria-label=\"Slide {{ i + 1 }}\"></button>
            {% endfor %}
        </div>

        <div class=\"carousel-inner\">
            {% for oa in oaListe|slice(0, 10) %}
                <div class=\"carousel-item {% if loop.index0 == 0 %}active{% endif %}\">
                    <div class=\"container py-4\">
                        <div class=\"row align-items-center justify-content-center\">
                            <div class=\"col-md-4 text-center\">

                                {% if oa.posterPath %}
                                    <a href=\"index.php?controleur=oa&methode=afficher{{ oa.type == 'Film' ? 'Film' : 'Serie' }}&idOa={{ oa.idOa }}\">
                                        <img src=\"{{ oa.posterPath }}\" alt=\"Affiche de {{ oa.nom }}\" class=\"img-fluid rounded shadow-sm\" style=\"max-height: 400px; object-fit: cover;\" loading=\"lazy\">
                                    </a>
\t\t\t\t\t\t\t\t{% else %}                                
                                    <p><em>Affiche non disponible</em></p>
                                {% endif %}
                            </div>
                            <div class=\"col-md-6 text-center\">
                                <h2 class=\"text-uppercase text-dark fs-3 fw-bold\">{{ oa.nom }}</h2>
                                <p><strong>Date de sortie :</strong> {{ oa.dateSortie|date('d/m/Y') }}</p>
                                <p><strong>Description :</strong></p>
                                <p class=\"text-muted\">{{ oa.description|slice(0, 200) }}...</p>
                                <p><strong>Note :</strong>
                                    {% for i in 1..5 %}
                                        <img src=\"{{ i <= (oa.note / 2) ? 'img/noteRemplie.png' : 'img/noteVide.png' }}\" alt=\"Note\" width=\"30\" height=\"30\" loading=\"lazy\">
                                    {% endfor %}
                                </p>
                                {% if oa.type == 'Film' %}
                                    <a href=\"index.php?controleur=oa&methode=afficherFilm&idOa={{ oa.idOa }}\" class=\"btn btn-primary mt-2\">Découvrir</a>
                                {% else %}
                                    <a href=\"index.php?controleur=oa&methode=afficherSerie&idOa={{ oa.idOa }}\" class=\"btn btn-primary mt-2\">Découvrir</a>
                                {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
        <br>

        <button class=\"carousel-control-prev\" type=\"button\" data-bs-target=\"#carouselExample\" data-bs-slide=\"prev\" >
            <span class=\"carousel-control-prev-icon\" style=\" width: 70px; height: 70px;\" aria-hidden=\"true\"></span>
            <span class=\"visually-hidden\">Précédent</span>
        </button>
        <button class=\"carousel-control-next\" type=\"button\" data-bs-target=\"#carouselExample\" data-bs-slide=\"next\">
            <span class=\"carousel-control-next-icon\" style=\" width: 70px; height: 70px;\" aria-hidden=\"true\"></span>
            <span class=\"visually-hidden\">Suivant</span>
        </button>
    </div>

    <h2 class=\"text-center mt-5\">À la une</h2>
    <div class=\"container mt-4\">

        <div class=\"row row-cols-1 row-cols-md-5 g-4\">
            {% for oa in oaListe|slice(10,20) %}
                <div class=\"col\">
                {% if oa.type == 'Film' %}
                    <a href=\"index.php?controleur=oa&methode=afficherFilm&idOa={{ oa.idOa }}\" class=\"text-decoration-none\">
                {% else %}
                    <a href=\"index.php?controleur=oa&methode=afficherSerie&idOa={{ oa.idOa }}\" class=\"text-decoration-none\">
                {% endif %}
                        <div class=\"card h-100 shadow-sm\">
                            {% if oa.posterPath %}
                                <img src=\"{{ oa.posterPath }}\" class=\"card-img-top\" alt=\"Affiche de {{ oa.nom }}\" style=\"height: 250px; object-fit: cover;\" loading=\"lazy\">
                            {% else %}
                                <div class=\"card-img-top text-center py-5 bg-light\" style=\"height: 250px;\">Affiche non disponible</div>
                            {% endif %}
                            <div class=\"card-body text-center\">
                                <h6 class=\"card-title text-truncate\">{{ oa.nom }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            {% endfor %}
        </div>
    </div>

    <h2 class=\"text-center mt-5\">Messages les plus likés</h2>
    <div class=\"container mt-4\">
        <div class=\"row row-cols-1 row-cols-md-3 g-4\">
            {% for message in topMessages %}
                <div class=\"col\">
                    <div class=\"card shadow-sm h-100\">
                        <div class=\"card-body text-center\">
                            <a href=\"index.php?controleur=utilisateur&methode=afficherAutreUtilisateur&pseudo={{ message.pseudo }}\">
                                <img src=\"{{ 'img/profils/' ~ message.photoProfil }}\" 
                                    alt=\"Photo de profil de {{ message.pseudo }}\" 
                                    class=\"rounded-circle mb-3 border border-3\" 
                                    style=\"width: 80px; height: 80px; object-fit: cover;\" loading=\"lazy\">
                            </a>
                            <h5 class=\"text-primary fw-bold\">{{ message.pseudo }}</h5>
                            <p class=\"text-muted small mb-1\">Forum : <strong>{{ message.forumNom }}</strong></p>
                            <p class=\"text-center text-muted\" style=\"font-size: 0.9rem;\">
                                {{ message.contenu|slice(0, 100) ~ '...' }}
                            </p>
                            <p class=\"text-danger small mb-0\">
                                <i class=\"fas fa-heart\"></i> {{ message.nbLike }} likes
                            </p>
                            <a href=\"index.php?controleur=message&methode=listerMessage&idForum={{ message.idForum }}\" 
                            class=\"btn btn-outline-primary btn-sm mt-3\">Voir le forum</a>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>



    <h2 class=\"text-center mt-5\">Découvrir plus d'œuvres</h2>
    <div id=\"decouvrirOaContainer\" class=\"container mt-4\">
        <div class=\"row row-cols-1 row-cols-md-5 g-4\">
            {% for oa in oaRandomListe %}
                <div class=\"col\">
                    <a href=\"index.php?controleur=oa&methode=afficherFilm&idOa={{ oa.idOa }}\" class=\"text-decoration-none\">
                        <div class=\"card h-100 shadow-sm\">
                            {% if oa.posterPath %}
                                <img src=\"{{ oa.posterPath }}\" class=\"card-img-top\" alt=\"Affiche de {{ oa.nom }}\" style=\"height: 250px; object-fit: cover;\" loading=\"lazy\">
                            {% else %}
                                <div class=\"card-img-top text-center py-5 bg-light\" style=\"height: 250px;\">Affiche non disponible</div>
                            {% endif %}
                            <div class=\"card-body text-center\">
                                <h6 class=\"card-title text-truncate\">{{ oa.nom }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            {% endfor %}
        </div>
    </div>

    <div class=\"text-center mt-4\">
        <button id=\"loadRandomBtn\" class=\"btn btn-primary\">Découvrir toujours plus !</button>
    </div>
\t<br><br>
{% endif %}

{% endblock %}

{% block scripts %}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loadRandomBtn = document.getElementById('loadRandomBtn');

    if (loadRandomBtn) {
        loadRandomBtn.addEventListener('click', function () {
            // Adapte le chemin en fonction du dossier dans lequel se trouve ton projet :
            fetch('index.php?controleur=oa&methode=decouvrirPlus')
                .then(response => {
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);

                        // On cible le container puis la div .row
                        const containerEl = document.getElementById('decouvrirOaContainer');
                        if (!containerEl) {
                            console.error('Container #decouvrirOaContainer non trouvé.');
                            return;
                        }
                        const row = containerEl.querySelector('.row');

                        if (!Array.isArray(data) || data.length === 0 || data.error) {
                            console.error('Erreur dans les données :', data.error || 'Aucune œuvre récupérée.');
                            alert('Impossible de charger de nouvelles œuvres.');
                            return;
                        }

                        // Ajoute chaque œuvre dans le container
                        data.forEach(oa => {
                            row.innerHTML += `
                                <div class=\"col\">
                                    <a href=\"index.php?controleur=oa&methode=\${oa.type == 'Film' ? 'afficherFilm' : 'afficherSerie'}&idOa=\${oa.idOa}\" class=\"text-decoration-none\">
                                        <div class=\"card h-100 shadow-sm\">
                                            \${oa.posterPath ? `<img src=\"\${oa.posterPath}\" class=\"card-img-top\" alt=\"Affiche de \${oa.nom}\" style=\"height: 250px; object-fit: cover;\" loading=\"lazy\">`
                                            : `<div class=\"card-img-top text-center py-5 bg-light\" style=\"height: 250px;\">Affiche non disponible</div>`}
                                            <div class=\"card-body text-center\">
                                                <h6 class=\"card-title text-truncate\">\${oa.nom}</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        });
                    } catch(e) {
                        console.error('Erreur lors du parsing du JSON:', e);
                        alert('Erreur de connexion avec le serveur.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du fetch:', error);
                    alert('Erreur de connexion avec le serveur.');
                });
        });
    }
});
</script>
{% endblock %}
", "index.html.twig", "/opt/lampp/htdocs/Video-Home-Share/templates/index.html.twig");
    }
}
