
<link rel="stylesheet" href="../../../../modules/productsscripts/editor/codemirror.css" type="text/css">
<link rel="stylesheet" href="../../../../modules/productsscripts/editor/icecoder.css" type="text/css">
<script src="../../../../modules/productsscripts/editor/codemirror.js"  type="text/javascript"></script>
<script src="../../../../modules/productsscripts/editor/javascript.js"  type="text/javascript"></script>
<script src="../../../../modules/productsscripts/editor/css.js"  type="text/javascript"></script>
<script src="../../../../modules/productsscripts/editor/autorefresh.js"  type="text/javascript"></script>


<br>
<div class="translations tabbable">
<div class="translationsFields tab-content ">
{foreach from=$languages item="lang"}
<div class="tab-pane translation-label-{$lang.iso_code}
{if $lang.id_lang == $default_lang}
active
{/if}
">
<h2>Script</h2>
<textarea id="scripts_{$lang.id_lang}" name="scripts_{$lang.id_lang}"  style="width:100%; height: 250px; display:none; border-radius:4px;">{$valores[{$lang@iteration-1}].scripts} </textarea>
<script>
$(document).ready(function(){
var editor_{$lang.id_lang} = CodeMirror.fromTextArea(document.getElementById('scripts_{$lang.id_lang}'), {
  lineNumbers: true,
  mode: "javascript",
  theme: "icecoder",
  autoRefresh: true
});

 });
</script>
<br><br>
<h2>CSS</h2>
<textarea id="csss_{$lang.id_lang}" name="csss_{$lang.id_lang}" class="form-control" style="width:100%; height: 250px; display:none; border-radius:4px;">{$valores[{$lang@iteration-1}].csss}</textarea>
<script>
$(document).ready(function(){
var editor2_{$lang.id_lang} = CodeMirror.fromTextArea(document.getElementById('csss_{$lang.id_lang}'), {
  lineNumbers: true,
  mode: "css",
  theme: "icecoder",
  autoRefresh: true
});

 });
</script>
</div>

  {/foreach}
</div>
</div>
