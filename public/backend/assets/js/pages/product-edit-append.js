$(document).ready(function () {
    // Count existing rows from edit form
    var materialCount = $(".add-more-material-append .row").length - 1;
    var ingredientsCount = $(".add-more-ingredients-append .row").length - 1;
    var specificationsCount = $(".add-more-specifications-append .row").length - 1;
    var additionalFeatureCount = $(".add-more-additional-feature-append .row").length - 1;

    // ---------- MATERIAL ----------
    $('.add-more-material').on('click', function () {
        materialCount++;
        var newMaterial = `
        <div class="row" id="material-row-${materialCount}">
            <div class="col-lg-10">
                <div class="mb-1">
                    <input type="text" name="product_material[]" id="product_material_${materialCount}" class="form-control" placeholder="Enter material">
                </div>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger remove-material btn-sm" data-id="${materialCount}">Remove</button>
            </div>
        </div>`;
        $('.add-more-material-append').append(newMaterial);
    });

    $(document).on('click', '.remove-material', function () {
        var rowId = $(this).data('id');
        $(`#material-row-${rowId}`).remove();
    });

    // ---------- INGREDIENTS ----------
    $('.add-more-ingredients').on('click', function () {
        ingredientsCount++;
        var newIngredients = `
        <div class="row" id="ingredients-row-${ingredientsCount}">
            <div class="col-lg-10">
                <div class="mb-1">
                    <input type="text" name="product_ingredients[]" id="product_ingredients_${ingredientsCount}" class="form-control" placeholder="Enter ingredients">
                </div>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger remove-ingredients btn-sm" data-id="${ingredientsCount}">Remove</button>
            </div>
        </div>`;
        $('.add-more-ingredients-append').append(newIngredients);
    });

    $(document).on('click', '.remove-ingredients', function () {
        var rowId = $(this).data('id');
        $(`#ingredients-row-${rowId}`).remove();
    });

    // ---------- SPECIFICATIONS ----------
    $('.add-more-specifications').on('click', function () {
        specificationsCount++;
        var newSpecifications = `
        <div class="row" id="specifications-row-${specificationsCount}">
            <div class="col-lg-10">
                <div class="mb-1">
                    <input type="text" name="product_specifications[]" id="product_specifications_${specificationsCount}" class="form-control" placeholder="Enter specifications">
                </div>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger remove-specifications btn-sm" data-id="${specificationsCount}">Remove</button>
            </div>
        </div>`;
        $('.add-more-specifications-append').append(newSpecifications);
    });

    $(document).on('click', '.remove-specifications', function () {
        var rowId = $(this).data('id');
        $(`#specifications-row-${rowId}`).remove();
    });

    // ---------- ADDITIONAL FEATURE ----------
    $('.add-more-additional-feature').on('click', function () {
        additionalFeatureCount++;
        var new_additional_feature = `
        <div class="row" id="additional-feature-row-${additionalFeatureCount}">
            <div class="col-lg-10">
                <div class="mb-1">
                    <input type="text" name="additional_feature_key_value[]" id="additional-feature-key-value-${additionalFeatureCount}" class="form-control" placeholder="Enter additional feature value">
                </div>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger remove-feature btn-sm" data-id="${additionalFeatureCount}">Remove</button>
            </div>
        </div>`;
        $('.add-more-additional-feature-append').append(new_additional_feature);
    });

    $(document).on('click', '.remove-feature', function () {
        var rowId = $(this).data('id');
        $(`#additional-feature-row-${rowId}`).remove();
    });
});
