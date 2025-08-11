<?php

/** @var yii\web\View $this */

$this->title = 'Adminisztrációs Panel';
?>
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="row row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"/><path d="M12 3v3m0 12v3"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    132 Értékesítés
                                </div>
                                <div class="text-muted">
                                    12 függőben lévő fizetés
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    78 Rendelés
                                </div>
                                <div class="text-muted">
                                    32 kiszállítva
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-twitter text-white avatar">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    623 Megosztás
                                </div>
                                <div class="text-muted">
                                    16 ma
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-facebook text-white avatar">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    132 Kedvelés
                                </div>
                                <div class="text-muted">
                                    21 ma
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Forgalom Összesítő</h3>
                <div id="chart-revenue-bg" class="chart-sm"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Legutóbbi Tevékenységek</h3>
                <div class="divide-y">
                    <div>
                        <div class="row">
                            <div class="col-auto">
                                <span class="avatar">JL</span>
                            </div>
                            <div class="col">
                                <div class="text-truncate">
                                    <strong>Jeffie Lewzey</strong> hozzászólt a <strong>"Nem vagyok boszorkány."</strong> bejegyzéshez.
                                </div>
                                <div class="text-muted">24 órája</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-auto">
                                <span class="avatar" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                            </div>
                            <div class="col">
                                <div class="text-truncate">
                                    <strong>Mallory Hulme</strong> születésnapja van. Kívánjunk neki jót!
                                </div>
                                <div class="text-muted">most</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-auto">
                                <span class="avatar">DS</span>
                            </div>
                            <div class="col">
                                <div class="text-truncate">
                                    <strong>Dunn Slane</strong> bejegyzést tett közzé <strong>"Nos, mit akarsz?"</strong>.
                                </div>
                                <div class="text-muted">most</div>
                            </div>
                        </div>
                    </div>
                    <div>
        <div class="row">
                            <div class="col-auto">
                                <span class="avatar">EL</span>
                            </div>
                            <div class="col">
                                <div class="text-truncate">
                                    <strong>Emmy Levet</strong> létrehozott egy új projektet <strong>Reggeli ébresztőóra</strong>.
                                </div>
                                <div class="text-muted">4 napja</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Leggyakrabban Meglátogatott Oldalak</h3>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-muted">
                        Keresés:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" aria-label="Oldal keresése">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Oldal név</th>
                            <th>Látogatók</th>
                            <th>Egyedi</th>
                            <th>Visszapattanási arány</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="text-muted">001401</span></td>
                            <td><a href="#" class="text-reset" tabindex="-1">/</a></td>
                            <td>
                                4,896
                            </td>
                            <td class="text-muted">
                                3,654
                            </td>
                            <td>
                                82.54%
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Műveletek</button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            Művelet
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            Másik művelet
                                        </a>
                                    </div>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-muted">001402</span></td>
                            <td><a href="#" class="text-reset" tabindex="-1">/form-elements.html</a></td>
                            <td>
                                3,652
                            </td>
                            <td class="text-muted">
                                3,215
                            </td>
                            <td>
                                76.29%
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Műveletek</button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            Művelet
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            Másik művelet
                                        </a>
                                    </div>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-muted">001403</span></td>
                            <td><a href="#" class="text-reset" tabindex="-1">/index.html</a></td>
                            <td>
                                3,256
                            </td>
                            <td class="text-muted">
                                2,865
                            </td>
                            <td>
                                72.65%
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Műveletek</button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            Művelet
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            Másik művelet
                                        </a>
                                    </div>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-muted">001404</span></td>
                            <td><a href="#" class="text-reset" tabindex="-1">/icons.html</a></td>
                            <td>
                                986
                            </td>
                            <td class="text-muted">
                                865
                            </td>
                            <td>
                                44.89%
                            </td>
                            <td class="text-end">
                                <span class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">Műveletek</button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            Művelet
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            Másik művelet
                                        </a>
                                    </div>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
