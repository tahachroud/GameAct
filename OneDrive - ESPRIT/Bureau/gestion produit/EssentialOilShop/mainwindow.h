#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QSqlDatabase>
#include <QSqlQuery>
#include <QSqlError>
#include <QSqlQueryModel>
#include <QMessageBox>
#include <QDialog>
#include <QVBoxLayout>
#include <QHBoxLayout>
#include <QLabel>
#include <QTableWidget>
#include <QHeaderView>
#include <QtCharts/QChartView>
#include <QtCharts/QPieSeries>
#include <QtCharts/QPieSlice>
#include <QtCharts/QChart>

QT_BEGIN_NAMESPACE
namespace Ui {
class MainWindow;
}
QT_END_NAMESPACE

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = nullptr);
    ~MainWindow();

private slots:
    void on_btnAdd_clicked();
    void on_btnModify_clicked();
    void on_btnDelete_clicked();
    void on_btnClear_clicked();
    void on_btnRefresh_clicked();
    void on_btnStatistique_clicked();
    void on_productsTable_cellClicked(int row, int column);
    void on_searchBar_textChanged(const QString &text);
    void on_comboSort_currentIndexChanged(int index);

private:
    Ui::MainWindow *ui;
    QSqlDatabase db;

    bool createConnection();
    void loadProducts();
    void clearForm();
    void updateStats();
    void filterAndSortTable(const QString &searchText, int sortIndex);
};
#endif // MAINWINDOW_H
