(function ($) {
    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/the7-woocommerce-product-add-to-cart-v2.default", function ($widget, $) {
            $(function () {
                $widget.productVariations();
            })
        });
    });

    $.productVariations = function (el) {
        const $widget = $(el);
        const $variationList = $widget.find(".the7-vr-options");
        const $variationsData = $widget.find("[data-product_variations]");
        const $form = $widget.find("form");
        const $singleVariation = $form.find(".single_variation");
        let $insertedImage;
        let $backupImage;

        // Store a reference to the object.
        $.data(el, "productVariations", $widget);

        // Private methods.
        const methods = {
            init: function () {
                if ($variationList.length) {

                    $("li a", $variationList).on("click", function (e) {
                        e.preventDefault();

                        const $this = $(this);
                        const $parent = $this.parent();
                        const $currentVariation = $this.closest("ul");
                        const atr = $.escapeSelector($currentVariation.attr("data-atr"));
                        const $select = $currentVariation.siblings("select#" + atr);

                        const productVariations =  $variationsData.data("product_variations");
                        const oldState = {
                            [atr]: $parent.hasClass("active") ? String($this.data("id")) : ""
                        };
                        const newState = {
                            [atr]: $parent.hasClass("active") ? "" : String($this.data("id"))
                        };
                        const variationImageState = Object.entries(Object.assign({}, oldState, newState))
                            .reduce((acc, [key, value]) => {
                                if (value) acc['attribute_' + key] = value;
                                return acc;
                        }, {});
                        //methods.getVariationImage(variationImageState);
                        methods.updateProductImage(methods.getVariationImage(variationImageState));

                        if ($parent.hasClass("active")) {
                            $parent.removeClass("active");
                            $select.val("").trigger("change");
                        } else {
                            const id = $this.attr("data-id");

                            // Set variation active.
                            $parent.siblings().removeClass("active");
                            $parent.addClass("active");

                            $select.val(id).trigger("change");
                        }
                    });
                  
                    

                    $form.find(".variations select").each(function () {
                        const $this = $(this);
                        const val = $this.val();

                        if (val.length) {
                            const atr = $.escapeSelector($this.attr("id"));
                            $variationList.filter("[data-atr='" + atr + "']").find("li a[data-id='" + val + "']").trigger("click");
                        }
                    });
                }

                const applyPriceBottomMargin = function () {
                    $singleVariation.children().not(":empty").last().addClass("last");
                }

                $widget.find(".single_variation_wrap").on("show_variation", function (event, variation) {
                    applyPriceBottomMargin();
                });

                applyPriceBottomMargin();
            },
            getProductSlider: function () {
                if (typeof sliderObject === 'undefined') {
                    const $slider = $widget.closest('.product').find('.elementor-widget-the7-woocommerce-product-images-slider');

                    if ($slider.length) {
                        sliderObject = $slider.data('the7Slider').getSwiper();
                    } else {
                        sliderObject = null;
                    }
                }

                return sliderObject;
            },

            replaceImage: function ($imageToReplace, $imageToInsert) {
                if (!$backupImage) {
                    $backupImage = $imageToReplace.first().clone();
                }

                $widget.addClass("replace-is-loading");

                $insertedImage = $();
                $imageToReplace.each(function () {
                    $(this).replaceWith(function () {
                        const $img = $imageToInsert.clone();
                        $img.on("load", function () {
                            $widget.removeClass("replace-is-loading");
                        });

                        // Store inserted image so it can be replaced by the backup image.
                        $insertedImage = $insertedImage.add($img);
                        return $img;
                    });
                });

                $widget.addClass("has-replaced-img");
                $widget.closest('.product').find('.elementor-widget-the7-woocommerce-product-images-slider').layzrInitialisation();
            },

            restoreImage: function () {
                if ($insertedImage && $insertedImage.length && $backupImage && $backupImage.length) {
                    methods.replaceImage($insertedImage, $backupImage);
                    $insertedImage = undefined;
                    $widget.removeClass('has-replaced-img');
                }
            },
            updateProductImage: function ($imgElement) {
                if (!$imgElement || !$imgElement.length) {
                    methods.restoreImage();
                    return;
                }


                const slider = methods.getProductSlider();

                if (slider) {
                    const $slideToImage = $(slider.wrapperEl).find('img[src="' + $imgElement.attr('data-src') + '"]').first();

                    if ($slideToImage.length) {
                        methods.restoreImage();
                        slider.slideTo(slider.slides.indexOf($slideToImage.closest('.the7-swiper-slide')[0]));
                    } else {
                        slider.slideTo(0);
                        const $imageToReplace = slider.slides.length === 1 ? $(slider.slides[0]).find('img') : $(slider.$wrapperEl).find('.the7-swiper-slide[data-swiper-slide-index="0"]').find('img');
                        methods.replaceImage($imageToReplace, $imgElement);
                    }
                }
            },
             /**
             * Get the image HTML of the first variation, based on provided state.
             */
            getVariationImage: function (state) {
                const foundVariation = methods.findFirstMatchingVariationOfGivenState(state);

                if (!foundVariation) {
                    return undefined;
                }

                const image = foundVariation.image;

                return $(`
                        <img
                            class="replaced-img preload-me aspect lazy lazy-load"
                            style="--ratio: ${image.src_w} / ${image.src_h};"
                            width="${image.src_w}"
                            height="${image.src_h}"
                            data-src="${image.src}"
                            data-srcset="${image.srcset || ""}"
                            alt="${image.alt || ""}"
                            title="${image.title || ""}"
                            data-caption="${image.data_caption || ""}"
                            loading="eager"
                            data-large_image="${image.full_src}"
                            data-large_image_width="${image.full_src_w}"
                            data-large_image_height="${image.full_src_h}"
                            sizes="${image.sizes}"
                        />
                    `);
            },
            /**
             * Find first matching variation with image for attributes.
             *
             * @returns {{}}
             */
            findFirstMatchingVariationOfGivenState(attributes) {
                if (!attributes || Object.keys(attributes).length === 0) {
                    return undefined;
                }

                const variations =  $variationsData.data("product_variations");
                for (let i = 0; i < variations.length; i++) {
                    let variation = variations[i];

                    if (methods.isExactMatch(attributes, variation.attributes)) {
                        return variation;
                    }
                }

                return undefined;
            },
             /**
             * See if attributes match.
             *
             * @return {Boolean}
             */
            isExactMatch(variation_attributes, attributes) {
                var match = true;
                for (var attr_name in variation_attributes) {
                    if (variation_attributes.hasOwnProperty(attr_name)) {
                        var val1 = variation_attributes[attr_name];
                        var val2 = attributes[attr_name];
                        if (val1 === undefined || val2 === undefined || val2.length === 0 || (val1.length !== 0 && val1 !== val2)) {
                            match = false;
                        }
                    }
                }
                return match;
            }

        };

        methods.init();
    };

    $.fn.productVariations = function () {
        return this.each(function () {
            if ($(this).data("productVariations") !== undefined) {
                $(this).removeData("productVariations")
            }
            new $.productVariations(this);
        });
    };
})(jQuery);
