<link rel="stylesheet" type="text/css" href="[{ $oViewConf->getResourceUrl() }]marm_slider.css">
<script type="text/javascript" src="[{ $oViewConf->getResourceUrl() }]jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="[{ $oViewConf->getResourceUrl() }]marm_slider.js"></script>
[{assign var=oBanners value=$oView->getBanners() }]
[{assign var="currency" value=$oView->getActCurrency()}]
[{if $oBanners}]	<div id="marm-slider-wrap">		<a id="marmprev" class="marmcontrols" title="zurück">zurück</a>		<a id="marmnext" class="marmcontrols" title="weiter">weiter</a>
		<div id="marm-slider">
			[{foreach from=$oBanners item=oBanner }]
				[{assign var=oArticle value=$oBanner->getBannerArticle() }]
				[{assign var=sBannerLink value=$oBanner->getBannerLink() }]				
				[{if $sBannerLink }]
					<a href="[{ $sBannerLink }]">
				[{/if}]				
				[{if $oArticle }]
				<span class="marmBox">
					<strong class="marmTitle">[{ $oArticle->oxarticles__oxtitle->value }]</strong>
					<strong class="marmPrice">[{ $oArticle->getFPrice() }] [{ $currency->sign}]</strong>
				</span>
				[{/if }]
				[{assign var=sBannerPictureUrl value=$oBanner->getBannerPictureUrl() }]
				[{if $sBannerPictureUrl }]
					<img src="[{ $sBannerPictureUrl }]" alt="[{$oBanner->oxactions__oxtitle->value}]" onclick="emos_userEvent1('marker','Startseite_Wechselteaser/[{$oBanner->oxactions__oxtitle->value}]');">
				[{/if }]
				[{if $sBannerLink }]
					</a>
				[{/if}]
			[{/foreach }]
		</div>	</div>
[{/if }]