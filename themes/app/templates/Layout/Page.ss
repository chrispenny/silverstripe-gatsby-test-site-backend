<% include Header %>

<main class="container">
    <div class="row">
        <article class="col">
            <h1>$Title</h1>
            $Content
        </article>
    </div>

    <% if $Form %>
        $Form
    <% end_if %>
</main>

<% include Footer %>

<flyout-panel
    :open="mobileMenuOpen"
    @close="closeMobileMenu"
>
    <h1>Flyout Panel</h1>
</flyout-panel>
