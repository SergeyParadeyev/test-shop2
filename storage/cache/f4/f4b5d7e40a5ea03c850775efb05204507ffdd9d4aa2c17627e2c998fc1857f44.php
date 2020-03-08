<?php

/* extension/module/dd.twig */
class __TwigTemplate_70f8b9a1d75de0d830461814b2a15e435885cb1e4405fcc4dca28204d87e6115 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo (isset($context["header"]) ? $context["header"] : null);
        echo (isset($context["column_left"]) ? $context["column_left"] : null);
        echo "
<div id=\"content\" xmlns=\"http://www.w3.org/1999/html\">
  <div class=\"page-header\">
    <div class=\"container-fluid\">
      <div class=\"pull-right\">
        <button type=\"submit\" form=\"form-dd\" data-toggle=\"tooltip\" title=\"";
        // line 6
        echo (isset($context["button_save"]) ? $context["button_save"] : null);
        echo "\" class=\"btn btn-primary\"><i class=\"fa fa-save\"></i></button>
        <a href=\"";
        // line 7
        echo (isset($context["cancel"]) ? $context["cancel"] : null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo (isset($context["button_cancel"]) ? $context["button_cancel"] : null);
        echo "\" class=\"btn btn-default\"><i class=\"fa fa-reply\"></i></a></div>
      <h1>";
        // line 8
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h1>
      <ul class=\"breadcrumb\">
        ";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["breadcrumbs"]) ? $context["breadcrumbs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 11
            echo "        <li><a href=\"";
            echo $this->getAttribute($context["breadcrumb"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["breadcrumb"], "text", array());
            echo "</a></li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "      </ul>
    </div>
  </div>
  <div class=\"container-fluid\">
    ";
        // line 17
        if ((isset($context["error_warning"]) ? $context["error_warning"] : null)) {
            // line 18
            echo "    <div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> ";
            echo (isset($context["error_warning"]) ? $context["error_warning"] : null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>
    ";
        }
        // line 22
        echo "    <div class=\"panel panel-default\">
      <div class=\"panel-heading\">
        <h3 class=\"panel-title\"><i class=\"fa fa-pencil\"></i> ";
        // line 24
        echo (isset($context["text_edit"]) ? $context["text_edit"] : null);
        echo "</h3>
      </div>
      <div class=\"panel-body\">
        <form action=\"";
        // line 27
        echo (isset($context["action"]) ? $context["action"] : null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\" id=\"form-dd\" class=\"form-horizontal\">
          <div class=\"form-group\">
            <label class=\"col-sm-2 control-label\" for=\"input-status\">";
        // line 29
        echo (isset($context["entry_status"]) ? $context["entry_status"] : null);
        echo "</label>
            <div class=\"col-sm-10\">
              <select name=\"module_dd_status\" id=\"input-status\" class=\"form-control\">
                ";
        // line 32
        if ((isset($context["module_dd_status"]) ? $context["module_dd_status"] : null)) {
            // line 33
            echo "                  <option value=\"1\" selected=\"selected\">";
            echo (isset($context["text_enabled"]) ? $context["text_enabled"] : null);
            echo "</option>
                  <option value=\"0\">";
            // line 34
            echo (isset($context["text_disabled"]) ? $context["text_disabled"] : null);
            echo "</option>
                ";
        } else {
            // line 36
            echo "                  <option value=\"1\">";
            echo (isset($context["text_enabled"]) ? $context["text_enabled"] : null);
            echo "</option>
                  <option value=\"0\" selected=\"selected\">";
            // line 37
            echo (isset($context["text_disabled"]) ? $context["text_disabled"] : null);
            echo "</option>
                ";
        }
        // line 39
        echo "              </select>
            </div>
          </div>
          <hr>
          <p>Импорт товара от поставщика 
            <a href=\"https://distributions.com.ua/about_company\">\"Прямые-дистрибуции\"</a>
          </p>
          <p>Ссылка берется из раздела Прайсы -> Контент для YML</p>
          <ul>Импортируются:
            <li>\"Категории\"</li>
            <li>Группы атрибутов с наименованием как \"Категории\"</li>
            <li>Товар</li>
            <li>Атрибуты товара</li>
            <li>Загружаются все картинки для товара</li>
          </ul>
          <p>Если товар по наименованию уже есть в магазине, проверяется его количество и цена. Если они отличаются, то товар обновляется.</p>
          <hr>
            <label class=\"col-sm-4 control-label\" for=\"input-xmlurl\">";
        // line 56
        echo (isset($context["entry_xmlurl"]) ? $context["entry_xmlurl"] : null);
        echo "</label>
            <div class=\"col-sm-8\">
                ";
        // line 58
        if ((isset($context["module_dd_xmlurl"]) ? $context["module_dd_xmlurl"] : null)) {
            // line 59
            echo "                  <input  class=\"form-control\" type=\"text\" name=\"module_dd_xmlurl\" id=\"input_xmlurl\" value=\"";
            echo (isset($context["module_dd_xmlurl"]) ? $context["module_dd_xmlurl"] : null);
            echo "\">
                ";
        } else {
            // line 61
            echo "                  <input  class=\"form-control\" type=\"text\" name=\"module_dd_xmlurl\" id=\"input_xmlurl\">
                ";
        }
        // line 63
        echo "            </div>
          <div class=\"form-group\">
          </div>
        </form>
        <button class=\"btn btn-primary center-block\" id=\"dd_import\">Импорт</button>

        <script type=\"text/javascript\">

          if( \$('#input_xmlurl').val() == '' ){
            \$('#dd_import').attr('disabled', true);
          } else {
            \$('#dd_import').attr('disabled', false);
          }

          \$('#input_xmlurl').change( function(){

            if( \$('#input_xmlurl').val() == '' ){
              \$('#dd_import').attr('disabled', true);
            } else {
              \$('#dd_import').attr('disabled', false);
            }

          });

          \$('#dd_import').click(function () {
            console.log('onClick');
            \$('#dd_import').html('Ждите...');
            \$('#dd_import').attr('disabled', true);
            user_token = '";
        // line 91
        echo (isset($context["user_token"]) ? $context["user_token"] : null);
        echo "';
            console.log(user_token);
            \$.ajax({
              url: 'index.php?route=extension/module/dd/import&user_token=' + user_token,
              dataType: 'html',
              success: function(htmlText) {
                \$('#messages').append(htmlText);
                \$('#dd_import').html('Импорт');
                \$('#dd_import').attr('disabled', false);
              },
              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
              }
            });

          });
        </script>
        <hr>
        ";
        // line 109
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["categories"]) ? $context["categories"] : null));
        foreach ($context['_seq'] as $context["key"] => $context["category"]) {
            // line 110
            echo "          ";
            echo $context["key"];
            echo " => ";
            echo $this->getAttribute($context["category"], "name", array());
            echo " <br>
";
            // line 112
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['category'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 113
        echo "        <hr>
        ";
        // line 114
        if ((isset($context["messages"]) ? $context["messages"] : null)) {
            // line 115
            echo "            <div class='alert alert-info' id='messages'>";
            echo (isset($context["messages"]) ? $context["messages"] : null);
            echo "</div>
        ";
        }
        // line 117
        echo "      </div>
    </div>
  </div>
</div>
";
        // line 121
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "extension/module/dd.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  238 => 121,  232 => 117,  226 => 115,  224 => 114,  221 => 113,  215 => 112,  208 => 110,  204 => 109,  183 => 91,  153 => 63,  149 => 61,  143 => 59,  141 => 58,  136 => 56,  117 => 39,  112 => 37,  107 => 36,  102 => 34,  97 => 33,  95 => 32,  89 => 29,  84 => 27,  78 => 24,  74 => 22,  66 => 18,  64 => 17,  58 => 13,  47 => 11,  43 => 10,  38 => 8,  32 => 7,  28 => 6,  19 => 1,);
    }
}
/* {{ header }}{{ column_left }}*/
/* <div id="content" xmlns="http://www.w3.org/1999/html">*/
/*   <div class="page-header">*/
/*     <div class="container-fluid">*/
/*       <div class="pull-right">*/
/*         <button type="submit" form="form-dd" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>*/
/*         <a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>*/
/*       <h1>{{ heading_title }}</h1>*/
/*       <ul class="breadcrumb">*/
/*         {% for breadcrumb in breadcrumbs %}*/
/*         <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>*/
/*         {% endfor %}*/
/*       </ul>*/
/*     </div>*/
/*   </div>*/
/*   <div class="container-fluid">*/
/*     {% if error_warning %}*/
/*     <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}*/
/*       <button type="button" class="close" data-dismiss="alert">&times;</button>*/
/*     </div>*/
/*     {% endif %}*/
/*     <div class="panel panel-default">*/
/*       <div class="panel-heading">*/
/*         <h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_edit }}</h3>*/
/*       </div>*/
/*       <div class="panel-body">*/
/*         <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-dd" class="form-horizontal">*/
/*           <div class="form-group">*/
/*             <label class="col-sm-2 control-label" for="input-status">{{ entry_status }}</label>*/
/*             <div class="col-sm-10">*/
/*               <select name="module_dd_status" id="input-status" class="form-control">*/
/*                 {% if module_dd_status %}*/
/*                   <option value="1" selected="selected">{{ text_enabled }}</option>*/
/*                   <option value="0">{{ text_disabled }}</option>*/
/*                 {% else %}*/
/*                   <option value="1">{{ text_enabled }}</option>*/
/*                   <option value="0" selected="selected">{{ text_disabled }}</option>*/
/*                 {% endif %}*/
/*               </select>*/
/*             </div>*/
/*           </div>*/
/*           <hr>*/
/*           <p>Импорт товара от поставщика */
/*             <a href="https://distributions.com.ua/about_company">"Прямые-дистрибуции"</a>*/
/*           </p>*/
/*           <p>Ссылка берется из раздела Прайсы -> Контент для YML</p>*/
/*           <ul>Импортируются:*/
/*             <li>"Категории"</li>*/
/*             <li>Группы атрибутов с наименованием как "Категории"</li>*/
/*             <li>Товар</li>*/
/*             <li>Атрибуты товара</li>*/
/*             <li>Загружаются все картинки для товара</li>*/
/*           </ul>*/
/*           <p>Если товар по наименованию уже есть в магазине, проверяется его количество и цена. Если они отличаются, то товар обновляется.</p>*/
/*           <hr>*/
/*             <label class="col-sm-4 control-label" for="input-xmlurl">{{ entry_xmlurl }}</label>*/
/*             <div class="col-sm-8">*/
/*                 {% if module_dd_xmlurl %}*/
/*                   <input  class="form-control" type="text" name="module_dd_xmlurl" id="input_xmlurl" value="{{ module_dd_xmlurl }}">*/
/*                 {% else %}*/
/*                   <input  class="form-control" type="text" name="module_dd_xmlurl" id="input_xmlurl">*/
/*                 {% endif %}*/
/*             </div>*/
/*           <div class="form-group">*/
/*           </div>*/
/*         </form>*/
/*         <button class="btn btn-primary center-block" id="dd_import">Импорт</button>*/
/* */
/*         <script type="text/javascript">*/
/* */
/*           if( $('#input_xmlurl').val() == '' ){*/
/*             $('#dd_import').attr('disabled', true);*/
/*           } else {*/
/*             $('#dd_import').attr('disabled', false);*/
/*           }*/
/* */
/*           $('#input_xmlurl').change( function(){*/
/* */
/*             if( $('#input_xmlurl').val() == '' ){*/
/*               $('#dd_import').attr('disabled', true);*/
/*             } else {*/
/*               $('#dd_import').attr('disabled', false);*/
/*             }*/
/* */
/*           });*/
/* */
/*           $('#dd_import').click(function () {*/
/*             console.log('onClick');*/
/*             $('#dd_import').html('Ждите...');*/
/*             $('#dd_import').attr('disabled', true);*/
/*             user_token = '{{ user_token }}';*/
/*             console.log(user_token);*/
/*             $.ajax({*/
/*               url: 'index.php?route=extension/module/dd/import&user_token=' + user_token,*/
/*               dataType: 'html',*/
/*               success: function(htmlText) {*/
/*                 $('#messages').append(htmlText);*/
/*                 $('#dd_import').html('Импорт');*/
/*                 $('#dd_import').attr('disabled', false);*/
/*               },*/
/*               error: function(xhr, ajaxOptions, thrownError) {*/
/*                 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/*               }*/
/*             });*/
/* */
/*           });*/
/*         </script>*/
/*         <hr>*/
/*         {% for key, category in categories %}*/
/*           {{ key }} => {{ category.name }} <br>*/
/* {#          <input type="checkbox" name="category_{{ key }}">{{ category.name }} <br>#}*/
/*         {% endfor %}*/
/*         <hr>*/
/*         {% if messages %}*/
/*             <div class='alert alert-info' id='messages'>{{ messages }}</div>*/
/*         {% endif %}*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* {{ footer }}*/
/* */
