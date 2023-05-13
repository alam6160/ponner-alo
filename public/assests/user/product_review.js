// review save
function save() {

    let rating = document.querySelector('input[name = rating]:checked');
    let review = document.getElementById('review');

    let base_url = window.location.origin;
    const api_url = `${base_url}/user/product-review/` + product_id;

    if(rating == null){
        var rate = null;
    }else{
        var rate = rating.value;
    }
    const form_data = {
        'rating': rate,
        'review': review.value,
    };

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(form_data),
    };

    fetch(api_url, options).then(response => {
        if ((response.ok) && (response.status === 200)) {
            return response.json();
        } else {
            return false;
        }
    }).then(data => {
        if (data.status == 0) {
            toastr.error(data['message']);
        } else if (data.status == 1) {
            toastr.success(data['message']);
            const r_data = document.getElementById('r_data');
            r_data.innerHTML = "";
            const post_html = `<ul class="review-list" id="list_body">
                                <li class="review-item" id="review_${data.save_data.id}">
                                    <div class="review-media">
                                        <h5 class="review-meta"><a
                                                href="#">${data.save_data.customer}</a><span>${data.save_data.created_at}</span>
                                        </h5>
                                    </div>
                                    <ul class="review-rating">
                                        <i class="${data.save_data.rating >= 1 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 2 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 3 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 4 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 5 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                    </ul>
                                    <p class="review-desc">${(data.save_data.review) != null ? data.save_data.review : 'N/A'}</p>
                                </li>
                                <button class="btn btn-success" onclick="edit(${data.save_data.id})"><i
                                            class="icofont-reply"></i>Edit</button>
                                <button class="btn btn-danger" onclick="delete_review(${data.save_data.id})"><i class="icofont-reply"></i>Delete</button>
                                </ul>`;
            r_data.innerHTML = post_html;

        }
    });
};

function edit(id) {
    const list_body = document.getElementById('list_body');
    list_body.innerHTML = "";

    let base_url = window.location.origin;
    let edit_url = `${base_url}/user/edit-review/` + id;

    fetch(edit_url).then(response => {
        return response.json();
    }).then(data => {
        const edit_temp = `<div id="review_body">
                    <h3 class="frame-title">Edit your review</h3>
                    <form class="review-form" onsubmit="return false;">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="star-rating">
                                    <input type="radio" name="rating" id="star-1"
                                        value="5" ${(data.review_data.rating) == 5 ? 'checked' : ''}><label for="star-1"></label>
                                    <input type="radio" name="rating" id="star-2"
                                        value="4" ${(data.review_data.rating) == 4 ? 'checked' : ''}><label for="star-2"></label>
                                    <input type="radio" name="rating" id="star-3"
                                        value="3" ${(data.review_data.rating) == 3 ? 'checked' : ''}><label for="star-3"></label>
                                    <input type="radio" name="rating" id="star-4"
                                        value="2" ${(data.review_data.rating) == 2 ? 'checked' : ''}><label for="star-4"></label>
                                    <input type="radio" name="rating" id="star-5"
                                        value="1" ${(data.review_data.rating) == 1 ? 'checked' : ''}><label for="star-5"></label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Describe" id="review">${(data.review_data.review) != null ? data.review_data.review : ''}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12" id="submit" onclick="edit_save(${data.review_data.id})"><button class="btn btn-inline"><i
                                        class="icofont-water-drop"></i><span>Save Review</span></button></div>
                        </div>
                    </form>
                </div>`
        list_body.innerHTML = edit_temp;
    })
}

function edit_save(id) {

    let rating = document.querySelector('input[name = rating]:checked');
    let review = document.getElementById('review');

    const base_url = window.location.origin;
    const api_url = `${base_url}/user/edit-review/` + id;

    if(rating == null){
        var rate = null;
    }else{
        var rate = rating.value;
    }

    const form_data = {
        'rating': rate,
        'review': review.value,
    };

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(form_data),
    };

    fetch(api_url, options).then(response => {
        if ((response.ok) && (response.status === 200)) {
            return response.json();
        } else {
            return false;
        }
    }).then(data => {
        if (data.status == 0) {
            toastr.error(data['message']);
        } else if (data.status == 1) {
            toastr.success(data['message']);
            const list_body = document.getElementById('list_body');
            list_body.innerHTML = "";
            const post_html = `<li class="review-item" id="review_${data.save_data.id}">
                                    <div class="review-media">
                                        <h5 class="review-meta"><a
                                                href="#">${data.save_data.customer}</a><span>${data.save_data.created_at}</span>
                                        </h5>
                                    </div>
                                    <ul class="review-rating">
                                        <i class="${data.save_data.rating >= 1 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 2 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 3 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 4 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                        <i class="${data.save_data.rating >= 5 ? 'icofont-ui-rating' : 'icofont-ui-rate-blank'}"></i>
                                    </ul>
                                    <p class="review-desc">${data.save_data.review}</p>
                                </li>
                                <button class="btn btn-success" onclick="edit(${data.save_data.id})"><i
                                            class="icofont-reply"></i>Edit</button>
                                <button class="btn btn-danger" onclick="delete_review(${data.save_data.id})"><i class="icofont-reply"></i>Delete</button>`;
            list_body.innerHTML = post_html;
        }
    });

}

function delete_review(id) {
    let base_url = window.location.origin;
    let delete_url = `${base_url}/user/delete-review/` + id;

    fetch(delete_url).then(response => {
        if ((response.ok) && (response.status === 200)) {
            return response.json();
        } else {
            return false;
        }
    }).then(data => {
        if (data.status == 0) {
            toastr.error(data['message']);
        } else if (data.status == 1) {
            toastr.success(data['message']);
            let list_body = document.getElementById('list_body');
            list_body.innerHTML = "";
            const edit_temp = `<div id="review_body">
                    <h3 class="frame-title">Add your review</h3>
                    <form class="review-form" onsubmit="return false;">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="star-rating">
                                    <input type="radio" name="rating" id="star-1"
                                        value="5"><label for="star-1"></label>
                                    <input type="radio" name="rating" id="star-2"
                                        value="4"><label for="star-2"></label>
                                    <input type="radio" name="rating" id="star-3"
                                        value="3"><label for="star-3"></label>
                                    <input type="radio" name="rating" id="star-4"
                                        value="2"><label for="star-4"></label>
                                    <input type="radio" name="rating" id="star-5"
                                        value="1"><label for="star-5"></label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Describe" id="review"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12" id="submit" onclick="save()"><button class="btn btn-inline"><i
                                        class="icofont-water-drop"></i><span>Save Review</span></button></div>
                        </div>
                    </form>
                </div>`
            list_body.innerHTML = edit_temp;
        }
    });
}