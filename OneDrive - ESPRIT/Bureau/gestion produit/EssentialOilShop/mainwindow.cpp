#include "mainwindow.h"
#include "ui_mainwindow.h"

#include <QSqlDatabase>
#include <QSqlQuery>
#include <QSqlError>
#include <QMessageBox>
#include <QDialog>
#include <QVBoxLayout>
#include <QHBoxLayout>
#include <QGridLayout>
#include <QLabel>
#include <QFrame>
#include <QHeaderView>
#include <QScrollArea>
#include <QtCharts/QChartView>
#include <QtCharts/QPieSeries>
#include <QtCharts/QPieSlice>
#include <QtCharts/QChart>
#include <QGraphicsDropShadowEffect>
#include <QButtonGroup>

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);

    if (!createConnection()) {
        QMessageBox::critical(this, "Database Error", "Could not connect to database!");
    }

    // Configure table
    ui->productsTable->horizontalHeader()->setSectionResizeMode(QHeaderView::Stretch);
    ui->productsTable->setEditTriggers(QAbstractItemView::NoEditTriggers);
    ui->productsTable->verticalHeader()->setVisible(false);

    // Load data
    loadProducts();

    // Connect signals
    connect(ui->searchBar, &QLineEdit::textChanged, this, &MainWindow::on_searchBar_textChanged);
    connect(ui->comboSort, QOverload<int>::of(&QComboBox::currentIndexChanged), this, &MainWindow::on_comboSort_currentIndexChanged);

    // --- Sidebar: prepare icons and exclusive check behavior ---
    QButtonGroup *sidebarGroup = new QButtonGroup(this);
    sidebarGroup->setExclusive(true);

    QList<QPushButton*> sidebarButtons = {
        ui->btnDashboard, ui->btnEmploye, ui->btnClient,
        ui->btnFinalProduct, ui->btnPressage, ui->btnMatiere, ui->btnEquipments, ui->btnAdmin
    };

    for (QPushButton *b : sidebarButtons) {
        if (!b) continue;
        b->setCheckable(true);
        sidebarGroup->addButton(b);
        b->setIconSize(QSize(18, 18));
    }

    // Set icons using platform style fallbacks (keeps UI portable)
    ui->btnDashboard->setIcon(style()->standardIcon(QStyle::SP_ComputerIcon));
    ui->btnEmploye->setIcon(style()->standardIcon(QStyle::SP_FileDialogDetailedView));
    ui->btnClient->setIcon(style()->standardIcon(QStyle::SP_DirHomeIcon));
    ui->btnFinalProduct->setIcon(style()->standardIcon(QStyle::SP_DriveDVDIcon));
    ui->btnPressage->setIcon(style()->standardIcon(QStyle::SP_DesktopIcon));
    ui->btnMatiere->setIcon(style()->standardIcon(QStyle::SP_DirOpenIcon));
    ui->btnEquipments->setIcon(style()->standardIcon(QStyle::SP_ToolBarHorizontalExtensionButton));
    ui->btnAdmin->setIcon(style()->standardIcon(QStyle::SP_BrowserReload));

    // Make dashboard the default active
    if (ui->btnDashboard) ui->btnDashboard->setChecked(true);
}

MainWindow::~MainWindow()
{
    delete ui;
}

// ==================== DATABASE CONNECTION ====================
bool MainWindow::createConnection()
{
    db = QSqlDatabase::addDatabase("QSQLITE");
    db.setDatabaseName("essential_oils.db");

    if (!db.open()) {
        return false;
    }

    QSqlQuery query;
    query.exec("CREATE TABLE IF NOT EXISTS products ("
               "id TEXT PRIMARY KEY, "
               "name TEXT NOT NULL, "
               "type TEXT NOT NULL, "
               "volume REAL, "
               "price REAL NOT NULL, "
               "stock INTEGER NOT NULL, "
               "description TEXT)");

    return true;
}

// ==================== LOAD PRODUCTS ====================
void MainWindow::loadProducts()
{
    ui->productsTable->setRowCount(0);

    QSqlQuery query("SELECT id, name, type, volume, price, stock FROM products");
    int row = 0;

    while (query.next()) {
        ui->productsTable->insertRow(row);
        ui->productsTable->setItem(row, 0, new QTableWidgetItem(query.value(0).toString()));
        ui->productsTable->setItem(row, 1, new QTableWidgetItem(query.value(1).toString()));
        ui->productsTable->setItem(row, 2, new QTableWidgetItem(query.value(2).toString()));
        ui->productsTable->setItem(row, 3, new QTableWidgetItem(query.value(3).toString()));
        ui->productsTable->setItem(row, 4, new QTableWidgetItem(QString::number(query.value(4).toDouble(), 'f', 2)));
        ui->productsTable->setItem(row, 5, new QTableWidgetItem(query.value(5).toString()));

        // Center-align all cells
        for (int col = 0; col < 6; col++) {
            ui->productsTable->item(row, col)->setTextAlignment(Qt::AlignCenter);
        }
        row++;
    }

    ui->totalBadge->setText(QString::number(row) + " products");
    updateStats();
}

// ==================== CLEAR FORM ====================
void MainWindow::clearForm()
{
    ui->inputProductId->clear();
    ui->inputProductName->clear();
    ui->comboOilType->setCurrentIndex(0);
    ui->inputPrice->clear();
    ui->inputVolume->clear();
    ui->inputStock->clear();
    ui->inputDescription->clear();
    ui->inputProductId->setEnabled(true);
}

// ==================== UPDATE STATS ====================
void MainWindow::updateStats()
{
    // This can be extended to update stat cards on the main page
}

// ==================== ADD PRODUCT ====================
void MainWindow::on_btnAdd_clicked()
{
    QString id = ui->inputProductId->text().trimmed();
    QString name = ui->inputProductName->text().trimmed();
    QString type = ui->comboOilType->currentText();
    QString priceStr = ui->inputPrice->text().trimmed();
    QString volumeStr = ui->inputVolume->text().trimmed();
    QString stockStr = ui->inputStock->text().trimmed();
    QString description = ui->inputDescription->toPlainText().trimmed();

    // Validation
    if (id.isEmpty() || name.isEmpty() || priceStr.isEmpty() || stockStr.isEmpty()) {
        QMessageBox::warning(this, "Validation Error", "Please fill in all required fields (ID, Name, Price, Stock).");
        return;
    }
    if (ui->comboOilType->currentIndex() == 0) {
        QMessageBox::warning(this, "Validation Error", "Please select an oil type.");
        return;
    }

    bool priceOk, stockOk;
    double price = priceStr.toDouble(&priceOk);
    int stock = stockStr.toInt(&stockOk);
    double volume = volumeStr.toDouble();

    if (!priceOk || price < 0) {
        QMessageBox::warning(this, "Validation Error", "Please enter a valid price.");
        return;
    }
    if (!stockOk || stock < 0) {
        QMessageBox::warning(this, "Validation Error", "Please enter a valid stock quantity.");
        return;
    }

    // Check if ID already exists
    QSqlQuery checkQuery;
    checkQuery.prepare("SELECT COUNT(*) FROM products WHERE id = :id");
    checkQuery.bindValue(":id", id);
    checkQuery.exec();
    if (checkQuery.next() && checkQuery.value(0).toInt() > 0) {
        QMessageBox::warning(this, "Duplicate ID", "A product with this ID already exists.");
        return;
    }

    QSqlQuery query;
    query.prepare("INSERT INTO products (id, name, type, volume, price, stock, description) "
                  "VALUES (:id, :name, :type, :volume, :price, :stock, :description)");
    query.bindValue(":id", id);
    query.bindValue(":name", name);
    query.bindValue(":type", type);
    query.bindValue(":volume", volume);
    query.bindValue(":price", price);
    query.bindValue(":stock", stock);
    query.bindValue(":description", description);

    if (query.exec()) {
        QMessageBox::information(this, "Success", "Product added successfully!");
        clearForm();
        loadProducts();
        // Re-apply current filter/sort
        filterAndSortTable(ui->searchBar->text(), ui->comboSort->currentIndex());
    } else {
        QMessageBox::critical(this, "Error", "Failed to add product:\n" + query.lastError().text());
    }
}

// ==================== MODIFY PRODUCT ====================
void MainWindow::on_btnModify_clicked()
{
    QString id = ui->inputProductId->text().trimmed();
    QString name = ui->inputProductName->text().trimmed();
    QString type = ui->comboOilType->currentText();
    QString priceStr = ui->inputPrice->text().trimmed();
    QString volumeStr = ui->inputVolume->text().trimmed();
    QString stockStr = ui->inputStock->text().trimmed();
    QString description = ui->inputDescription->toPlainText().trimmed();

    if (id.isEmpty()) {
        QMessageBox::warning(this, "Selection Error", "Please select a product from the table to modify.");
        return;
    }
    if (name.isEmpty() || priceStr.isEmpty() || stockStr.isEmpty()) {
        QMessageBox::warning(this, "Validation Error", "Please fill in all required fields.");
        return;
    }
    if (ui->comboOilType->currentIndex() == 0) {
        QMessageBox::warning(this, "Validation Error", "Please select an oil type.");
        return;
    }

    bool priceOk, stockOk;
    double price = priceStr.toDouble(&priceOk);
    int stock = stockStr.toInt(&stockOk);
    double volume = volumeStr.toDouble();

    if (!priceOk || price < 0) {
        QMessageBox::warning(this, "Validation Error", "Please enter a valid price.");
        return;
    }
    if (!stockOk || stock < 0) {
        QMessageBox::warning(this, "Validation Error", "Please enter a valid stock quantity.");
        return;
    }

    QSqlQuery query;
    query.prepare("UPDATE products SET name=:name, type=:type, volume=:volume, "
                  "price=:price, stock=:stock, description=:description WHERE id=:id");
    query.bindValue(":id", id);
    query.bindValue(":name", name);
    query.bindValue(":type", type);
    query.bindValue(":volume", volume);
    query.bindValue(":price", price);
    query.bindValue(":stock", stock);
    query.bindValue(":description", description);

    if (query.exec()) {
        QMessageBox::information(this, "Success", "Product modified successfully!");
        clearForm();
        loadProducts();
        filterAndSortTable(ui->searchBar->text(), ui->comboSort->currentIndex());
    } else {
        QMessageBox::critical(this, "Error", "Failed to modify product:\n" + query.lastError().text());
    }
}

// ==================== DELETE PRODUCT ====================
void MainWindow::on_btnDelete_clicked()
{
    QString id = ui->inputProductId->text().trimmed();

    if (id.isEmpty()) {
        QMessageBox::warning(this, "Selection Error", "Please select a product from the table to delete.");
        return;
    }

    QMessageBox::StandardButton reply;
    reply = QMessageBox::question(this, "Confirm Delete",
                                  "Are you sure you want to delete product \"" + id + "\"?",
                                  QMessageBox::Yes | QMessageBox::No);

    if (reply == QMessageBox::Yes) {
        QSqlQuery query;
        query.prepare("DELETE FROM products WHERE id = :id");
        query.bindValue(":id", id);

        if (query.exec()) {
            QMessageBox::information(this, "Success", "Product deleted successfully!");
            clearForm();
            loadProducts();
            filterAndSortTable(ui->searchBar->text(), ui->comboSort->currentIndex());
        } else {
            QMessageBox::critical(this, "Error", "Failed to delete product:\n" + query.lastError().text());
        }
    }
}

// ==================== CLEAR BUTTON ====================
void MainWindow::on_btnClear_clicked()
{
    clearForm();
}

// ==================== REFRESH BUTTON ====================
void MainWindow::on_btnRefresh_clicked()
{
    loadProducts();
    ui->searchBar->clear();
    ui->comboSort->setCurrentIndex(0);
}

// ==================== TABLE CELL CLICKED ====================
void MainWindow::on_productsTable_cellClicked(int row, int column)
{
    Q_UNUSED(column);

    QString id = ui->productsTable->item(row, 0)->text();
    ui->inputProductId->setText(id);
    ui->inputProductId->setEnabled(false); // Lock ID when editing
    ui->inputProductName->setText(ui->productsTable->item(row, 1)->text());

    // Set combo box to matching type
    QString type = ui->productsTable->item(row, 2)->text();
    int typeIndex = ui->comboOilType->findText(type);
    if (typeIndex >= 0) {
        ui->comboOilType->setCurrentIndex(typeIndex);
    }

    ui->inputVolume->setText(ui->productsTable->item(row, 3)->text());
    ui->inputPrice->setText(ui->productsTable->item(row, 4)->text());
    ui->inputStock->setText(ui->productsTable->item(row, 5)->text());

    // Load description from database
    QSqlQuery query;
    query.prepare("SELECT description FROM products WHERE id = :id");
    query.bindValue(":id", id);
    if (query.exec() && query.next()) {
        ui->inputDescription->setText(query.value(0).toString());
    }
}

// ==================== SEARCH BAR ====================
void MainWindow::on_searchBar_textChanged(const QString &text)
{
    filterAndSortTable(text, ui->comboSort->currentIndex());
}

// ==================== SORT COMBO ====================
void MainWindow::on_comboSort_currentIndexChanged(int index)
{
    filterAndSortTable(ui->searchBar->text(), index);
}

// ==================== FILTER AND SORT TABLE ====================
void MainWindow::filterAndSortTable(const QString &searchText, int sortIndex)
{
    // First, reload all products
    ui->productsTable->setRowCount(0);

    // Build query with optional search
    QString queryStr = "SELECT id, name, type, volume, price, stock FROM products";
    QString searchTrimmed = searchText.trimmed();

    if (!searchTrimmed.isEmpty()) {
        queryStr += " WHERE id LIKE :search OR name LIKE :search2 OR type LIKE :search3";
    }

    // Add ORDER BY based on sort index
    switch (sortIndex) {
    case 1: queryStr += " ORDER BY id ASC"; break;
    case 2: queryStr += " ORDER BY id DESC"; break;
    case 3: queryStr += " ORDER BY name ASC"; break;
    case 4: queryStr += " ORDER BY name DESC"; break;
    case 5: queryStr += " ORDER BY type ASC"; break;
    case 6: queryStr += " ORDER BY type DESC"; break;
    case 7: queryStr += " ORDER BY volume ASC"; break;
    case 8: queryStr += " ORDER BY volume DESC"; break;
    case 9: queryStr += " ORDER BY price ASC"; break;
    case 10: queryStr += " ORDER BY price DESC"; break;
    case 11: queryStr += " ORDER BY stock ASC"; break;
    case 12: queryStr += " ORDER BY stock DESC"; break;
    default: break;
    }

    QSqlQuery query;
    query.prepare(queryStr);

    if (!searchTrimmed.isEmpty()) {
        QString pattern = "%" + searchTrimmed + "%";
        query.bindValue(":search", pattern);
        query.bindValue(":search2", pattern);
        query.bindValue(":search3", pattern);
    }

    query.exec();

    int row = 0;
    while (query.next()) {
        ui->productsTable->insertRow(row);
        ui->productsTable->setItem(row, 0, new QTableWidgetItem(query.value(0).toString()));
        ui->productsTable->setItem(row, 1, new QTableWidgetItem(query.value(1).toString()));
        ui->productsTable->setItem(row, 2, new QTableWidgetItem(query.value(2).toString()));
        ui->productsTable->setItem(row, 3, new QTableWidgetItem(query.value(3).toString()));
        ui->productsTable->setItem(row, 4, new QTableWidgetItem(QString::number(query.value(4).toDouble(), 'f', 2)));
        ui->productsTable->setItem(row, 5, new QTableWidgetItem(query.value(5).toString()));

        for (int col = 0; col < 6; col++) {
            ui->productsTable->item(row, col)->setTextAlignment(Qt::AlignCenter);
        }
        row++;
    }

    ui->totalBadge->setText(QString::number(row) + " products");
}

// ==================== STATISTICS BUTTON ====================
void MainWindow::on_btnStatistique_clicked()
{
    // Create dialog
    QDialog *statDialog = new QDialog(this);
    statDialog->setWindowTitle("Product Statistics");
    statDialog->setMinimumSize(950, 620);
    statDialog->setStyleSheet(
        "QDialog { background-color: #E7F2D7; }"
    );

    QHBoxLayout *mainDialogLayout = new QHBoxLayout(statDialog);
    mainDialogLayout->setSpacing(16);
    mainDialogLayout->setContentsMargins(16, 16, 16, 16);

    // =============== LEFT SIDE: PIE CHART ===============
    QFrame *chartFrame = new QFrame();
    chartFrame->setObjectName("chartFrame");
    chartFrame->setStyleSheet(
        "#chartFrame { background-color: #F5FAF0; border: 1px solid #D0E0C0; border-radius: 14px; }");
    QVBoxLayout *chartLayout = new QVBoxLayout(chartFrame);
    chartLayout->setContentsMargins(18, 18, 18, 18);
    chartLayout->setSpacing(10);

    QLabel *chartTitle = new QLabel("Distribution by Oil Type");
    chartTitle->setStyleSheet("font-size: 16px; font-weight: 700; color: #2F3E1E;");
    chartLayout->addWidget(chartTitle);

    // Build pie chart data from database
    QPieSeries *series = new QPieSeries();
    QSqlQuery typeQuery("SELECT type, COUNT(*) as cnt FROM products GROUP BY type ORDER BY cnt DESC");

    QStringList colors;
    colors << "#6B9A55" << "#5A9BD5" << "#E0A050" << "#9A7BD5"
           << "#E07070" << "#50C8C8" << "#D5A05A" << "#7A9E64"
           << "#C06090" << "#60A0C0";

    int colorIdx = 0;
    int totalProducts = 0;

    while (typeQuery.next()) {
        QString typeName = typeQuery.value(0).toString();
        int count = typeQuery.value(1).toInt();
        totalProducts += count;

        QPieSlice *slice = series->append(typeName + " (" + QString::number(count) + ")", count);
        slice->setColor(QColor(colors[colorIdx % colors.size()]));
        slice->setLabelVisible(true);
        slice->setLabelColor(QColor("#2F3E1E"));
        slice->setLabelFont(QFont("Segoe UI", 9, QFont::Bold));
        colorIdx++;
    }

    // If no data
    if (totalProducts == 0) {
        QPieSlice *emptySlice = series->append("No Data", 1);
        emptySlice->setColor(QColor("#D0E0C0"));
        emptySlice->setLabelVisible(true);
        emptySlice->setLabelColor(QColor("#5D7A4A"));
    }

    series->setHoleSize(0.35);

    QChart *chart = new QChart();
    chart->addSeries(series);
    chart->setTitle("");
    chart->setAnimationOptions(QChart::SeriesAnimations);
    chart->legend()->setVisible(false);
    chart->setBackgroundBrush(Qt::transparent);
    chart->setBackgroundPen(Qt::NoPen);
    chart->setMargins(QMargins(0, 0, 0, 0));
    chart->setBackgroundRoundness(0);

    QChartView *chartView = new QChartView(chart);
    chartView->setRenderHint(QPainter::Antialiasing);
    chartView->setStyleSheet("background: transparent; border: none;");
    chartView->setBackgroundBrush(Qt::transparent);
    chartView->setMinimumHeight(380);

    chartLayout->addWidget(chartView, 1);
    mainDialogLayout->addWidget(chartFrame, 1);

    // =============== RIGHT SIDE: DETAILED STATS ===============
    QFrame *statsFrame = new QFrame();
    statsFrame->setObjectName("statsFrame");
    statsFrame->setStyleSheet(
        "#statsFrame { background-color: #F5FAF0; border: 1px solid #D0E0C0; border-radius: 14px; }");
    QVBoxLayout *statsMainLayout = new QVBoxLayout(statsFrame);
    statsMainLayout->setContentsMargins(18, 18, 18, 18);
    statsMainLayout->setSpacing(10);

    QLabel *statsTitle = new QLabel("Detailed Statistics");
    statsTitle->setStyleSheet("font-size: 16px; font-weight: 700; color: #2F3E1E;");
    statsMainLayout->addWidget(statsTitle);

    // Scroll area for stats content
    QScrollArea *scrollArea = new QScrollArea();
    scrollArea->setWidgetResizable(true);
    scrollArea->setStyleSheet(
        "QScrollArea { border: none; background: transparent; }"
        "QScrollArea > QWidget > QWidget { background: transparent; }"
        "QScrollBar:vertical { background: #F0F5EA; width: 8px; border-radius: 4px; }"
        "QScrollBar::handle:vertical { background: #C0D4B0; border-radius: 4px; min-height: 30px; }"
        "QScrollBar::handle:vertical:hover { background: #A0C490; }"
        "QScrollBar::add-line:vertical, QScrollBar::sub-line:vertical { height: 0; }"
    );

    QWidget *scrollContent = new QWidget();
    scrollContent->setStyleSheet("background: transparent;");
    QVBoxLayout *statsLayout = new QVBoxLayout(scrollContent);
    statsLayout->setSpacing(6);
    statsLayout->setContentsMargins(2, 0, 2, 0);

    // ---- General Stats ----
    QSqlQuery generalQuery;

    // Total products
    generalQuery.exec("SELECT COUNT(*) FROM products");
    int totalCount = 0;
    if (generalQuery.next()) totalCount = generalQuery.value(0).toInt();

    // Total stock value
    generalQuery.exec("SELECT SUM(price * stock) FROM products");
    double totalValue = 0;
    if (generalQuery.next()) totalValue = generalQuery.value(0).toDouble();

    // Average price
    generalQuery.exec("SELECT AVG(price) FROM products");
    double avgPrice = 0;
    if (generalQuery.next()) avgPrice = generalQuery.value(0).toDouble();

    // Total stock units
    generalQuery.exec("SELECT SUM(stock) FROM products");
    int totalStock = 0;
    if (generalQuery.next()) totalStock = generalQuery.value(0).toInt();

    // Min/Max price
    generalQuery.exec("SELECT MIN(price), MAX(price) FROM products");
    double minPrice = 0, maxPrice = 0;
    if (generalQuery.next()) {
        minPrice = generalQuery.value(0).toDouble();
        maxPrice = generalQuery.value(1).toDouble();
    }

    // Most expensive product
    generalQuery.exec("SELECT name, price FROM products ORDER BY price DESC LIMIT 1");
    QString mostExpName = "-";
    double mostExpPrice = 0;
    if (generalQuery.next()) {
        mostExpName = generalQuery.value(0).toString();
        mostExpPrice = generalQuery.value(1).toDouble();
    }

    // Cheapest product
    generalQuery.exec("SELECT name, price FROM products ORDER BY price ASC LIMIT 1");
    QString cheapestName = "-";
    double cheapestPrice = 0;
    if (generalQuery.next()) {
        cheapestName = generalQuery.value(0).toString();
        cheapestPrice = generalQuery.value(1).toDouble();
    }

    // Highest stock product
    generalQuery.exec("SELECT name, stock FROM products ORDER BY stock DESC LIMIT 1");
    QString highStockName = "-";
    int highStock = 0;
    if (generalQuery.next()) {
        highStockName = generalQuery.value(0).toString();
        highStock = generalQuery.value(1).toInt();
    }

    // Lowest stock product
    generalQuery.exec("SELECT name, stock FROM products ORDER BY stock ASC LIMIT 1");
    QString lowStockName = "-";
    int lowStock = 0;
    if (generalQuery.next()) {
        lowStockName = generalQuery.value(0).toString();
        lowStock = generalQuery.value(1).toInt();
    }

    // Number of distinct types
    generalQuery.exec("SELECT COUNT(DISTINCT type) FROM products");
    int distinctTypes = 0;
    if (generalQuery.next()) distinctTypes = generalQuery.value(0).toInt();

    // Average volume
    generalQuery.exec("SELECT AVG(volume) FROM products");
    double avgVolume = 0;
    if (generalQuery.next()) avgVolume = generalQuery.value(0).toDouble();

    // ---- Helper lambda to create stat rows ----
    auto addStatRow = [&](const QString &icon, const QString &label, const QString &value, const QString &valueColor) {
        QWidget *rowWidget = new QWidget();
        rowWidget->setStyleSheet(
            "QWidget { background-color: #FFFFFF; border: 1px solid #E8F0E0; border-radius: 8px; }");
        rowWidget->setMinimumHeight(38);
        rowWidget->setMaximumHeight(44);

        QHBoxLayout *rowLayout = new QHBoxLayout(rowWidget);
        rowLayout->setContentsMargins(12, 4, 12, 4);
        rowLayout->setSpacing(8);

        QLabel *iconLabel = new QLabel(icon);
        iconLabel->setStyleSheet("font-size: 15px; border: none; background: transparent;");
        iconLabel->setFixedWidth(22);
        rowLayout->addWidget(iconLabel);

        QLabel *textLabel = new QLabel(label);
        textLabel->setStyleSheet("font-size: 12px; color: #5D6C4C; font-weight: 500; border: none; background: transparent;");
        rowLayout->addWidget(textLabel, 1);

        QLabel *valueLabel = new QLabel(value);
        valueLabel->setStyleSheet(
            QString("font-size: 13px; font-weight: 700; color: %1; border: none; background: transparent;").arg(valueColor));
        valueLabel->setAlignment(Qt::AlignRight | Qt::AlignVCenter);
        rowLayout->addWidget(valueLabel);

        statsLayout->addWidget(rowWidget);
    };

    // ---- Helper for section headers ----
    auto addSectionHeader = [&](const QString &title) {
        QLabel *header = new QLabel(title);
        header->setStyleSheet("font-size: 12px; font-weight: 700; color: #5D7A4A; background: transparent; border: none; padding-top: 6px;");
        header->setAlignment(Qt::AlignCenter);
        statsLayout->addWidget(header);
    };

    // General section
    addSectionHeader("General Overview");

    addStatRow("📦", "Total Products", QString::number(totalCount), "#2F3E1E");
    addStatRow("🏷", "Distinct Oil Types", QString::number(distinctTypes), "#9A7BD5");
    addStatRow("📊", "Total Stock Units", QString::number(totalStock), "#5A9BD5");
    addStatRow("💰", "Total Stock Value", QString::number(totalValue, 'f', 2) + " DH", "#6B9A55");
    addStatRow("📈", "Average Price", QString::number(avgPrice, 'f', 2) + " DH", "#E0A050");
    addStatRow("📐", "Average Volume", QString::number(avgVolume, 'f', 1) + " ml", "#50C8C8");

    // Price section
    addSectionHeader("Price Analysis");

    addStatRow("⬆", "Highest Price", QString::number(maxPrice, 'f', 2) + " DH", "#E07070");
    addStatRow("⬇", "Lowest Price", QString::number(minPrice, 'f', 2) + " DH", "#6B9A55");
    addStatRow("🏆", "Most Expensive", mostExpName + " (" + QString::number(mostExpPrice, 'f', 2) + " DH)", "#E07070");
    addStatRow("💎", "Most Affordable", cheapestName + " (" + QString::number(cheapestPrice, 'f', 2) + " DH)", "#6B9A55");

    // Stock section
    addSectionHeader("Stock Analysis");

    addStatRow("📈", "Highest Stock", highStockName + " (" + QString::number(highStock) + ")", "#5A9BD5");
    addStatRow("📉", "Lowest Stock", lowStockName + " (" + QString::number(lowStock) + ")", "#E07070");

    // ---- Per-Type breakdown ----
    addSectionHeader("Per-Type Breakdown");

    QSqlQuery typeStatsQuery("SELECT type, COUNT(*) as cnt, AVG(price) as avg_p, SUM(stock) as total_s, "
                             "MIN(price) as min_p, MAX(price) as max_p FROM products GROUP BY type ORDER BY cnt DESC");

    int typeColorIdx = 0;
    while (typeStatsQuery.next()) {
        QString tName = typeStatsQuery.value(0).toString();
        int tCount = typeStatsQuery.value(1).toInt();
        double tAvgPrice = typeStatsQuery.value(2).toDouble();
        int tTotalStock = typeStatsQuery.value(3).toInt();
        double tMinPrice = typeStatsQuery.value(4).toDouble();
        double tMaxPrice = typeStatsQuery.value(5).toDouble();

        double percentage = (totalCount > 0) ? (tCount * 100.0 / totalCount) : 0;

        QWidget *typeWidget = new QWidget();
        typeWidget->setStyleSheet(
            "QWidget { background-color: #FFFFFF; border: 1px solid #E8F0E0; border-radius: 8px; }");

        QVBoxLayout *typeLayout = new QVBoxLayout(typeWidget);
        typeLayout->setContentsMargins(12, 8, 12, 8);
        typeLayout->setSpacing(3);

        // Type header row
        QHBoxLayout *typeNameRow = new QHBoxLayout();
        typeNameRow->setSpacing(6);

        QLabel *colorDot = new QLabel();
        colorDot->setFixedSize(10, 10);
        colorDot->setStyleSheet(
            QString("background-color: %1; border-radius: 5px; border: none;")
                .arg(colors[typeColorIdx % colors.size()]));
        typeNameRow->addWidget(colorDot);

        QLabel *typeNameLabel = new QLabel(tName);
        typeNameLabel->setStyleSheet("font-size: 13px; font-weight: 700; color: #2F3E1E; border: none; background: transparent;");
        typeNameRow->addWidget(typeNameLabel, 1);

        QLabel *pctLabel = new QLabel(QString::number(percentage, 'f', 1) + "%");
        pctLabel->setStyleSheet(
            QString("font-size: 12px; font-weight: 700; color: %1; border: none; background: transparent;")
                .arg(colors[typeColorIdx % colors.size()]));
        typeNameRow->addWidget(pctLabel);

        typeLayout->addLayout(typeNameRow);

        // Type details line
        QLabel *detailLabel = new QLabel(
            QString("Count: %1  |  Avg: %2 DH  |  Stock: %3  |  Range: %4-%5 DH")
                .arg(tCount)
                .arg(QString::number(tAvgPrice, 'f', 2))
                .arg(tTotalStock)
                .arg(QString::number(tMinPrice, 'f', 2))
                .arg(QString::number(tMaxPrice, 'f', 2)));
        detailLabel->setStyleSheet("font-size: 10px; color: #6B7A5A; border: none; background: transparent;");
        detailLabel->setWordWrap(true);
        typeLayout->addWidget(detailLabel);

        statsLayout->addWidget(typeWidget);
        typeColorIdx++;
    }

    statsLayout->addStretch();

    scrollArea->setWidget(scrollContent);
    statsMainLayout->addWidget(scrollArea, 1);

    mainDialogLayout->addWidget(statsFrame, 1);

    statDialog->exec();
    delete statDialog;
}
