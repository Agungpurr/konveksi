    </main>
    <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
        <div>
            &copy; <?= date('Y') ?> <strong><?= htmlspecialchars(APP_TITLE) ?></strong>.
            Studi Kasus: <strong><?= htmlspecialchars(KONVEKSI_NAME) ?></strong> (Bekasi).
        </div>
        <div class="text-secondary" style="font-size: 0.75rem;">
            <?= htmlspecialchars(APP_AUTHOR) ?> - NPM: <?= htmlspecialchars(APP_NPM) ?> | <?= htmlspecialchars(APP_INSTITUTION) ?>
        </div>
    </footer>
</div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

