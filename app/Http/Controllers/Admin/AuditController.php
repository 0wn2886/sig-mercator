<?php

namespace App\Http\Controllers\Admin;

// Ccosystem
// Information System
// Applications
// Administration
// Logique
// Physique
use Carbon\Carbon;
// PhpOffice
// see : https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/
use Illuminate\Support\Facades\DB;

class AuditController extends HomeController
{
    public function maturity()
    {
        $levels = $this->computeMaturity();

        $path = storage_path('app/levels-' . Carbon::today()->format('Ymd') . '.xlsx');

        $header = [
            'Object',
            'Count 1',
            'Total 1',
            'Maturity 1',
            'Count 2',
            'Total 2',
            'Maturity 2',
            'Count 3',
            'Total 3',
            'Maturity 3',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F77BE');

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true); // Objet
        $sheet->getColumnDimension('B')->setAutoSize(true); // Count 1
        $sheet->getColumnDimension('C')->setAutoSize(true); // Total 1
        $sheet->getColumnDimension('D')->setAutoSize(true); // % 1
        $sheet->getColumnDimension('E')->setAutoSize(true); // Count 2
        $sheet->getColumnDimension('F')->setAutoSize(true); // total 2
        $sheet->getColumnDimension('G')->setAutoSize(true); // % 2
        $sheet->getColumnDimension('H')->setAutoSize(true); // Count 3
        $sheet->getColumnDimension('I')->setAutoSize(true); // Total 3
        $sheet->getColumnDimension('J')->setAutoSize(true); // % 3

        // center cells
        $sheet->getStyle('B:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // percentage
        $sheet->getStyle('D')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        $sheet->getStyle('J')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

        // Initialise row count
        $row = 2;

        // ============
        // Ecosystem
        // ============
        $sheet->setCellValue("A{$row}", trans('cruds.menu.ecosystem.title_short'));
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // L1
        $sheet->setCellValue("B{$row}", '=sum(B'.($row + 1).':B'.($row + 2).')');
        $sheet->setCellValue("C{$row}", '=sum(C'.($row + 1).':C'.($row + 2).')');
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");

        // L2
        $sheet->setCellValue("E{$row}", '=sum(E'.($row + 1).':E'.($row + 2).')');
        $sheet->setCellValue("F{$row}", '=sum(F'.($row + 1).':F'.($row + 2).')');
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");

        // L3
        $sheet->setCellValue("H{$row}", '=sum(H'.($row + 1).':H'.($row + 2).')');
        $sheet->setCellValue("I{$row}", '=sum(I'.($row + 1).':I'.($row + 2).')');
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Entities
        $sheet->setCellValue("A{$row}", trans('cruds.entity.title'));
        $sheet->setCellValue("B{$row}", $levels['entities_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['entities']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['entities_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['entities']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['entities_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['entities']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;


        // Relations
        $sheet->setCellValue("A{$row}", trans('cruds.relation.title'));
        $sheet->setCellValue("B{$row}", $levels['relations_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['relations']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['relations_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['relations']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['relations_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['relations']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;


        // ============
        // Metier
        // ============
        $sheet->setCellValue("A{$row}", trans('cruds.menu.metier.title_short'));
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // L1
        $sheet->setCellValue("B{$row}", '=sum(B' . ($row+1) . ':B' . ($row+6) .')');
        $sheet->setCellValue("C{$row}", '=sum(C' . ($row+1) . ':C' . ($row+6) .')');
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");

        // L2
        $sheet->setCellValue("E{$row}", '=sum(E' . ($row+1) . ':E' . ($row+6) .')');
        $sheet->setCellValue("F{$row}", '=sum(F' . ($row+1) . ':F' . ($row+6) .')');
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");

        // L3
        $sheet->setCellValue("H{$row}", '=sum(H' . ($row+1) . ':H' . ($row+6) .')');
        $sheet->setCellValue("I{$row}", '=sum(I' . ($row+1) . ':I' . ($row+6) .')');
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // MacroProcessus
        $sheet->setCellValue("A{$row}", trans('cruds.macroProcessus.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", $levels['macroProcessuses_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['macroProcessuses']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['macroProcessuses_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['macroProcessuses']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Process
        $sheet->setCellValue("A{$row}", trans('cruds.process.title'));
        $sheet->setCellValue("B{$row}", $levels['processes_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['processes']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['processes_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['processes']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['processes_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['processes']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Activity
        $sheet->setCellValue("A{$row}", trans('cruds.activity.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", $levels['activities_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['activities']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['activities_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['activities']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Operation
        $sheet->setCellValue("A{$row}", trans('cruds.operation.title'));
        $sheet->setCellValue("B{$row}", $levels['operations_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['operations']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['operations_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['operations']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['operations_lvl3']);
        $sheet->setCellValue("I{$row}", $levels['operations']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Tâche
        $sheet->setCellValue("A{$row}", trans('cruds.task.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", '');
        $sheet->setCellValue("F{$row}", '');
        $sheet->setCellValue("G{$row}", '');
        $sheet->setCellValue("H{$row}", $levels['tasks_lvl3']);
        $sheet->setCellValue("I{$row}", $levels['tasks']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Acteur
        $sheet->setCellValue("A{$row}", trans('cruds.actor.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", $levels['actors_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['actors']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['actors_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['actors']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Information
        $sheet->setCellValue("A{$row}", trans('cruds.information.title'));
        $sheet->setCellValue("B{$row}", $levels['informations_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['informations']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['informations_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['informations']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['informations_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['informations']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;


        // ============
        // Application
        // ============
        $sheet->setCellValue("A{$row}", trans('cruds.menu.application.title_short'));
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // L1
        $sheet->setCellValue("B{$row}", '=sum(B' . ($row+1) . ':B' . ($row+6) .')');
        $sheet->setCellValue("C{$row}", '=sum(C' . ($row+1) . ':C' . ($row+6) .')');
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");

        // L2
        $sheet->setCellValue("E{$row}", '=sum(E' . ($row+1) . ':E' . ($row+6) .')');
        $sheet->setCellValue("F{$row}", '=sum(F' . ($row+1) . ':F' . ($row+6) .')');
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");

        // L3
        $sheet->setCellValue("H{$row}", '=sum(H' . ($row+1) . ':H' . ($row+6) .')');
        $sheet->setCellValue("I{$row}", '=sum(I' . ($row+1) . ':I' . ($row+6) .')');
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Block applicatif
        $sheet->setCellValue("A{$row}", trans('cruds.applicationBlock.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", $levels['applicationBlocks_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['applicationBlocks']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['applicationBlocks_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['applicationBlocks']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Applications
        $sheet->setCellValue("A{$row}", trans('cruds.application.title'));
        $sheet->setCellValue("B{$row}", $levels['applications_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['applications']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['applications_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['applications']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['applications_lvl3']);
        $sheet->setCellValue("I{$row}", $levels['applications']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // applicationService
        $sheet->setCellValue("A{$row}", trans('cruds.applicationService.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", $levels['applicationServices_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['applicationServices']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['applicationServices_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['applicationServices']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // applicationModule
        $sheet->setCellValue("A{$row}", trans('cruds.applicationModule.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", '');
        $sheet->setCellValue("E{$row}", $levels['applicationModules_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['applicationModules']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['applicationModules_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['applicationModules']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // database
        $sheet->setCellValue("A{$row}", trans('cruds.database.title'));
        $sheet->setCellValue("B{$row}", $levels['databases_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['databases']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['databases_lvl2']);
        $sheet->setCellValue("F{$row}", $levels['databases']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['databases_lvl2']);
        $sheet->setCellValue("I{$row}", $levels['databases']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // flux
        $sheet->setCellValue("A{$row}", trans('cruds.flux.title'));
        $sheet->setCellValue("B{$row}", $levels['fluxes_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['fluxes']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['fluxes_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['fluxes']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['fluxes_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['fluxes']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // ===============
        // Administration
        // ===============
        $sheet->setCellValue("A{$row}", trans('cruds.menu.administration.title_short'));
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // L1
        $sheet->setCellValue("B{$row}", '=sum(B' . ($row+1) . ':B' . ($row+5) .')');
        $sheet->setCellValue("C{$row}", '=sum(C' . ($row+1) . ':C' . ($row+5) .')');
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");

        // L2
        $sheet->setCellValue("E{$row}", '=sum(E' . ($row+1) . ':E' . ($row+5) .')');
        $sheet->setCellValue("F{$row}", '=sum(F' . ($row+1) . ':F' . ($row+5) .')');
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");

        // L3
        $sheet->setCellValue("H{$row}", '=sum(H' . ($row+1) . ':H' . ($row+5) .')');
        $sheet->setCellValue("I{$row}", '=sum(I' . ($row+1) . ':I' . ($row+5) .')');
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Zone
        $sheet->setCellValue("A{$row}", trans('cruds.zoneAdmin.title'));
        $sheet->setCellValue("B{$row}", $levels['zones_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['zones']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['zones_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['zones']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['zones_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['zones']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Annuaire
        $sheet->setCellValue("A{$row}", trans('cruds.annuaire.title'));
        $sheet->setCellValue("B{$row}", $levels['annuaires_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['annuaires']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['annuaires_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['annuaires']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['annuaires_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['annuaires']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Forest
        $sheet->setCellValue("A{$row}", trans('cruds.forestAd.title'));
        $sheet->setCellValue("B{$row}", $levels['forests_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['forests']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['forests_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['forests']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['forests_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['forests']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // Domaines
        $sheet->setCellValue("A{$row}", trans('cruds.domaineAd.title'));
        $sheet->setCellValue("B{$row}", $levels['domaines_lvl1']);
        $sheet->setCellValue("C{$row}", $levels['domaines']);
        $sheet->setCellValue("D{$row}", "=B{$row}/C{$row}");
        $sheet->setCellValue("E{$row}", $levels['domaines_lvl1']);
        $sheet->setCellValue("F{$row}", $levels['domaines']);
        $sheet->setCellValue("G{$row}", "=E{$row}/F{$row}");
        $sheet->setCellValue("H{$row}", $levels['domaines_lvl1']);
        $sheet->setCellValue("I{$row}", $levels['domaines']);
        $sheet->setCellValue("J{$row}", "=H{$row}/I{$row}");
        $row++;

        // ======================
        // Infrastructure logique
        // ======================
        $sheet->setCellValue("A{$row}", trans('cruds.menu.logical_infrastructure.title_short'));
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // =============================================================
        // Save sheet
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        // Return
        return response()->download($path);
        // =============================================================

        // L1
        $denominator = $levels['networks'] + $levels['subnetworks'] + $levels['gateways'] + $levels['switches'] + $levels['routers'] + $levels['securityDevices'] + $levels['logicalServers'];
        $sheet->setCellValue("B{$row}", $denominator);
        $sheet->setCellValue(
            "C{$row}",
            $denominator > 0 ?
            ($levels['networks_lvl1'] + $levels['subnetworks_lvl1'] + $levels['gateways_lvl1'] + $levels['switches_lvl1'] + $levels['routers_lvl1'] + $levels['securityDevices_lvl1'] + $levels['logicalServers_lvl1']) / $denominator
            : 0
        );

        // L2
        $denominator = $levels['networks'] + $levels['subnetworks'] + $levels['gateways'] + $levels['externalConnectedEntities']
            + $levels['switches'] + $levels['routers'] + $levels['securityDevices']
            + $levels['DHCPServers'] + $levels['DNSServers'] + $levels['logicalServers']
            + $levels['certificates'];
        $sheet->setCellValue("D{$row}", $denominator);
        $sheet->setCellValue(
            "E{$row}",
            $denominator > 0 ?
            ($levels['networks_lvl1'] + $levels['subnetworks_lvl1'] + $levels['gateways_lvl1'] + $levels['externalConnectedEntities_lvl2'] + $levels['DHCPServers_lvl2'] + $levels['DNSServers_lvl2'] + $levels['switches_lvl1'] + $levels['routers_lvl1'] + $levels['securityDevices_lvl1'] + $levels['logicalServers_lvl1'] + $levels['certificates_lvl2']) / $denominator
            : 0
        );

        // L3
        $denominator = $levels['networks'] + $levels['subnetworks'] + $levels['gateways'] + $levels['externalConnectedEntities']
            + $levels['switches'] + $levels['routers'] + $levels['securityDevices']
            + $levels['DHCPServers'] + $levels['DNSServers'] + $levels['logicalServers']
            + $levels['certificates'];
        $sheet->setCellValue("F{$row}", $denominator);
        $sheet->setCellValue(
            "G{$row}",
            $denominator > 0 ?
            ($levels['networks_lvl1'] + $levels['subnetworks_lvl1'] + $levels['gateways_lvl1'] + $levels['externalConnectedEntities_lvl2'] + $levels['DHCPServers_lvl2'] + $levels['DNSServers_lvl2'] + $levels['switches_lvl1'] + $levels['routers_lvl1'] + $levels['securityDevices_lvl1'] + $levels['logicalServers_lvl1'] + $levels['certificates_lvl2']) / $denominator
            : 0
        );
        $row++;

        // Network
        $sheet->setCellValue("A{$row}", trans('cruds.network.title'));
        $sheet->setCellValue("B{$row}", $levels['networks']);
        $sheet->setCellValue("C{$row}", $levels['networks'] > 0 ? $levels['networks_lvl1'] / $levels['networks'] : 0);
        $sheet->setCellValue("D{$row}", $levels['networks']);
        $sheet->setCellValue("E{$row}", $levels['networks'] > 0 ? $levels['networks_lvl1'] / $levels['networks'] : 0);
        $sheet->setCellValue("F{$row}", $levels['networks']);
        $sheet->setCellValue("G{$row}", $levels['networks'] > 0 ? $levels['networks_lvl1'] / $levels['networks'] : 0);
        $row++;

        // SubNetwork
        $sheet->setCellValue("A{$row}", trans('cruds.subnetwork.title'));
        $sheet->setCellValue("B{$row}", $levels['subnetworks']);
        $sheet->setCellValue("C{$row}", $levels['subnetworks'] > 0 ? $levels['subnetworks_lvl1'] / $levels['subnetworks'] : 0);
        $sheet->setCellValue("D{$row}", $levels['subnetworks']);
        $sheet->setCellValue("E{$row}", $levels['subnetworks'] > 0 ? $levels['subnetworks_lvl1'] / $levels['subnetworks'] : 0);
        $sheet->setCellValue("F{$row}", $levels['subnetworks']);
        $sheet->setCellValue("G{$row}", $levels['subnetworks'] > 0 ? $levels['subnetworks_lvl1'] / $levels['subnetworks'] : 0);
        $row++;

        // Gateway
        $sheet->setCellValue("A{$row}", trans('cruds.gateway.title'));
        $sheet->setCellValue("B{$row}", $levels['gateways']);
        $sheet->setCellValue("C{$row}", $levels['gateways'] > 0 ? $levels['gateways_lvl1'] / $levels['gateways'] : 0);
        $sheet->setCellValue("D{$row}", $levels['gateways']);
        $sheet->setCellValue("E{$row}", $levels['gateways'] > 0 ? $levels['gateways_lvl1'] / $levels['gateways'] : 0);
        $sheet->setCellValue("F{$row}", $levels['gateways']);
        $sheet->setCellValue("G{$row}", $levels['gateways'] > 0 ? $levels['gateways_lvl1'] / $levels['gateways'] : 0);
        $row++;

        // ExternalConnectedEntity
        $sheet->setCellValue("A{$row}", trans('cruds.externalConnectedEntity.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", $levels['externalConnectedEntities']);
        $sheet->setCellValue("E{$row}", $levels['externalConnectedEntities'] > 0 ? $levels['externalConnectedEntities_lvl2'] / $levels['externalConnectedEntities'] : 0);
        $sheet->setCellValue("F{$row}", $levels['externalConnectedEntities']);
        $sheet->setCellValue("G{$row}", $levels['externalConnectedEntities'] > 0 ? $levels['externalConnectedEntities_lvl2'] / $levels['externalConnectedEntities'] : 0);
        $row++;

        // NetworkSwitch
        $sheet->setCellValue("A{$row}", trans('cruds.networkSwitch.title'));
        $sheet->setCellValue("B{$row}", $levels['switches']);
        $sheet->setCellValue("C{$row}", $levels['switches'] > 0 ? $levels['switches_lvl1'] / $levels['switches'] : 0);
        $sheet->setCellValue("D{$row}", $levels['switches']);
        $sheet->setCellValue("E{$row}", $levels['switches'] > 0 ? $levels['switches_lvl1'] / $levels['switches'] : 0);
        $sheet->setCellValue("F{$row}", $levels['switches']);
        $sheet->setCellValue("G{$row}", $levels['switches'] > 0 ? $levels['switches_lvl1'] / $levels['switches'] : 0);
        $row++;

        // Router
        $sheet->setCellValue("A{$row}", trans('cruds.router.title'));
        $sheet->setCellValue("B{$row}", $levels['routers']);
        $sheet->setCellValue("C{$row}", $levels['routers'] > 0 ? $levels['routers_lvl1'] / $levels['routers'] : 0);
        $sheet->setCellValue("D{$row}", $levels['routers']);
        $sheet->setCellValue("E{$row}", $levels['routers'] > 0 ? $levels['routers_lvl1'] / $levels['routers'] : 0);
        $sheet->setCellValue("F{$row}", $levels['routers']);
        $sheet->setCellValue("G{$row}", $levels['routers'] > 0 ? $levels['routers_lvl1'] / $levels['routers'] : 0);
        $row++;

        // SecurityDevice
        $sheet->setCellValue("A{$row}", trans('cruds.securityDevice.title'));
        $sheet->setCellValue("B{$row}", $levels['securityDevices']);
        $sheet->setCellValue("C{$row}", $levels['securityDevices'] > 0 ? $levels['securityDevices_lvl1'] / $levels['securityDevices'] : 0);
        $sheet->setCellValue("D{$row}", $levels['securityDevices']);
        $sheet->setCellValue("E{$row}", $levels['securityDevices'] > 0 ? $levels['securityDevices_lvl1'] / $levels['securityDevices'] : 0);
        $sheet->setCellValue("F{$row}", $levels['securityDevices']);
        $sheet->setCellValue("G{$row}", $levels['securityDevices'] > 0 ? $levels['securityDevices_lvl1'] / $levels['securityDevices'] : 0);
        $row++;

        // DHCPServer
        $sheet->setCellValue("A{$row}", trans('cruds.dhcpServer.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", $levels['DHCPServers']);
        $sheet->setCellValue("E{$row}", $levels['DHCPServers'] > 0 ? $levels['DHCPServers_lvl2'] / $levels['DHCPServers'] : 0);
        $sheet->setCellValue("F{$row}", $levels['DHCPServers']);
        $sheet->setCellValue("G{$row}", $levels['DHCPServers'] > 0 ? $levels['DHCPServers_lvl2'] / $levels['DHCPServers'] : 0);
        $row++;

        // LogicalServer
        $sheet->setCellValue("A{$row}", trans('cruds.logicalServer.title'));
        $sheet->setCellValue("B{$row}", $levels['logicalServers']);
        $sheet->setCellValue("C{$row}", $levels['logicalServers'] > 0 ? $levels['logicalServers_lvl1'] / $levels['logicalServers'] : 0);
        $sheet->setCellValue("D{$row}", $levels['logicalServers']);
        $sheet->setCellValue("E{$row}", $levels['logicalServers'] > 0 ? $levels['logicalServers_lvl1'] / $levels['logicalServers'] : 0);
        $sheet->setCellValue("F{$row}", $levels['logicalServers']);
        $sheet->setCellValue("G{$row}", $levels['logicalServers'] > 0 ? $levels['logicalServers_lvl1'] / $levels['logicalServers'] : 0);
        $row++;

        // certificates
        $sheet->setCellValue("A{$row}", trans('cruds.certificate.title'));
        $sheet->setCellValue("B{$row}", '');
        $sheet->setCellValue("C{$row}", '');
        $sheet->setCellValue("D{$row}", $levels['certificates']);
        $sheet->setCellValue("E{$row}", $levels['certificates'] > 0 ? $levels['certificates_lvl2'] / $levels['certificates'] : 0);
        $sheet->setCellValue("F{$row}", $levels['certificates']);
        $sheet->setCellValue("G{$row}", $levels['certificates'] > 0 ? $levels['certificates_lvl2'] / $levels['certificates'] : 0);
        $row++;

        // =========================
        // Infrastructure physique
        // =========================
        $sheet->setCellValue("A{$row}", trans('cruds.menu.physical_infrastructure.title_short'));
        $sheet->getStyle("A{$row}:G{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:G{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // L1
        $denominator =
            $levels['sites'] + $levels['buildings'] + $levels['bays'] + $levels['physicalServers'] +
            $levels['phones'] + $levels['physicalRouters'] + $levels['physicalSwitchs'] +
            $levels['physicalSecurityDevices'] +
            $levels['wans'] + $levels['mans'] + $levels['lans'] + $levels['vlans'];
        $sheet->setCellValue("B{$row}", $denominator);
        $sheet->setCellValue(
            "C{$row}",
            $denominator > 0 ?
            ($levels['sites_lvl1'] + $levels['buildings_lvl1'] + $levels['bays_lvl1'] + $levels['physicalServers_lvl1'] +
            $levels['phones_lvl1'] + $levels['physicalRouters_lvl1'] + $levels['physicalSwitchs_lvl1'] +
            $levels['physicalSecurityDevices_lvl1'] +
            $levels['wans_lvl1'] + $levels['mans_lvl1'] + $levels['lans_lvl1'] + $levels['vlans_lvl1']) / $denominator
            : 0
        );

        // L2
        // $denominator=...
        $sheet->setCellValue("D{$row}", $denominator);
        $sheet->setCellValue(
            "E{$row}",
            $denominator > 0 ?
            ($levels['sites_lvl1'] + $levels['buildings_lvl1'] + $levels['bays_lvl1'] + $levels['physicalServers_lvl1'] +
            $levels['phones_lvl1'] + $levels['physicalRouters_lvl1'] + $levels['physicalSwitchs_lvl1'] +
            $levels['physicalSecurityDevices_lvl1'] +
            $levels['wans_lvl1'] + $levels['mans_lvl1'] + $levels['lans_lvl1'] + $levels['vlans_lvl1']) / $denominator
            : 0
        );

        // L3
        // $denominator=...
        $sheet->setCellValue("F{$row}", $denominator);
        $sheet->setCellValue(
            "G{$row}",
            $denominator > 0 ?
            ($levels['sites_lvl1'] + $levels['buildings_lvl1'] + $levels['bays_lvl1'] + $levels['physicalServers_lvl1'] +
            $levels['phones_lvl1'] + $levels['physicalRouters_lvl1'] + $levels['physicalSwitchs_lvl1'] +
            $levels['physicalSecurityDevices_lvl1'] +
            $levels['wans_lvl1'] + $levels['mans_lvl1'] + $levels['lans_lvl1'] + $levels['vlans_lvl1']) / $denominator
            : 0
        );
        $row++;

        // Site
        $sheet->setCellValue("A{$row}", trans('cruds.site.title'));
        $sheet->setCellValue("B{$row}", $levels['sites']);
        $sheet->setCellValue("C{$row}", $levels['sites'] > 0 ? $levels['sites_lvl1'] / $levels['sites'] : 0);
        $sheet->setCellValue("D{$row}", $levels['sites']);
        $sheet->setCellValue("E{$row}", $levels['sites'] > 0 ? $levels['sites_lvl1'] / $levels['sites'] : 0);
        $sheet->setCellValue("F{$row}", $levels['sites']);
        $sheet->setCellValue("G{$row}", $levels['sites'] > 0 ? $levels['sites_lvl1'] / $levels['sites'] : 0);
        $row++;

        // Building
        $sheet->setCellValue("A{$row}", trans('cruds.building.title'));
        $sheet->setCellValue("B{$row}", $levels['buildings']);
        $sheet->setCellValue("C{$row}", $levels['buildings'] > 0 ? $levels['buildings_lvl1'] / $levels['buildings'] : 0);
        $sheet->setCellValue("D{$row}", $levels['buildings']);
        $sheet->setCellValue("E{$row}", $levels['buildings'] > 0 ? $levels['buildings_lvl1'] / $levels['buildings'] : 0);
        $sheet->setCellValue("F{$row}", $levels['buildings']);
        $sheet->setCellValue("G{$row}", $levels['buildings'] > 0 ? $levels['buildings_lvl1'] / $levels['buildings'] : 0);
        $row++;

        // Bay
        $sheet->setCellValue("A{$row}", trans('cruds.bay.title'));
        $sheet->setCellValue("B{$row}", $levels['bays']);
        $sheet->setCellValue("C{$row}", $levels['bays'] > 0 ? $levels['bays_lvl1'] / $levels['bays'] : 0);
        $sheet->setCellValue("D{$row}", $levels['bays']);
        $sheet->setCellValue("E{$row}", $levels['bays'] > 0 ? $levels['bays_lvl1'] / $levels['bays'] : 0);
        $sheet->setCellValue("F{$row}", $levels['bays']);
        $sheet->setCellValue("G{$row}", $levels['bays'] > 0 ? $levels['bays_lvl1'] / $levels['bays'] : 0);
        $row++;

        // PhysicalServer
        $sheet->setCellValue("A{$row}", trans('cruds.physicalServer.title'));
        $sheet->setCellValue("B{$row}", $levels['physicalServers']);
        $sheet->setCellValue("C{$row}", $levels['physicalServers'] > 0 ? $levels['physicalServers_lvl1'] / $levels['physicalServers'] : 0);
        $sheet->setCellValue("D{$row}", $levels['physicalServers']);
        $sheet->setCellValue("E{$row}", $levels['physicalServers'] > 0 ? $levels['physicalServers_lvl1'] / $levels['physicalServers'] : 0);
        $sheet->setCellValue("F{$row}", $levels['physicalServers']);
        $sheet->setCellValue("G{$row}", $levels['physicalServers'] > 0 ? $levels['physicalServers_lvl1'] / $levels['physicalServers'] : 0);
        $row++;

        // Phone
        $sheet->setCellValue("A{$row}", trans('cruds.phone.title'));
        $sheet->setCellValue("B{$row}", $levels['phones']);
        $sheet->setCellValue("C{$row}", $levels['phones'] > 0 ? $levels['phones_lvl1'] / $levels['phones'] : 0);
        $sheet->setCellValue("D{$row}", $levels['phones']);
        $sheet->setCellValue("E{$row}", $levels['phones'] > 0 ? $levels['phones_lvl1'] / $levels['phones'] : 0);
        $sheet->setCellValue("F{$row}", $levels['phones']);
        $sheet->setCellValue("G{$row}", $levels['phones'] > 0 ? $levels['phones_lvl1'] / $levels['phones'] : 0);
        $row++;

        // PhysicalRouter
        $sheet->setCellValue("A{$row}", trans('cruds.physicalRouter.title'));
        $sheet->setCellValue("B{$row}", $levels['physicalRouters']);
        $sheet->setCellValue("C{$row}", $levels['physicalRouters'] > 0 ? $levels['physicalRouters_lvl1'] / $levels['physicalRouters'] : 0);
        $sheet->setCellValue("D{$row}", $levels['physicalRouters']);
        $sheet->setCellValue("E{$row}", $levels['physicalRouters'] > 0 ? $levels['physicalRouters_lvl1'] / $levels['physicalRouters'] : 0);
        $sheet->setCellValue("F{$row}", $levels['physicalRouters']);
        $sheet->setCellValue("G{$row}", $levels['physicalRouters'] > 0 ? $levels['physicalRouters_lvl1'] / $levels['physicalRouters'] : 0);
        $row++;

        // PhysicalSwitch
        $sheet->setCellValue("A{$row}", trans('cruds.physicalSwitch.title'));
        $sheet->setCellValue("B{$row}", $levels['physicalSwitchs']);
        $sheet->setCellValue("C{$row}", $levels['physicalSwitchs'] > 0 ? $levels['physicalSwitchs_lvl1'] / $levels['physicalSwitchs'] : 0);
        $sheet->setCellValue("D{$row}", $levels['physicalSwitchs']);
        $sheet->setCellValue("E{$row}", $levels['physicalSwitchs'] > 0 ? $levels['physicalSwitchs_lvl1'] / $levels['physicalSwitchs'] : 0);
        $sheet->setCellValue("F{$row}", $levels['physicalSwitchs']);
        $sheet->setCellValue("G{$row}", $levels['physicalSwitchs'] > 0 ? $levels['physicalSwitchs_lvl1'] / $levels['physicalSwitchs'] : 0);
        $row++;

        // PhysicalSecurityDevice
        $sheet->setCellValue("A{$row}", trans('cruds.physicalSecurityDevice.title'));
        $sheet->setCellValue("B{$row}", $levels['physicalSecurityDevices']);
        $sheet->setCellValue("C{$row}", $levels['physicalSecurityDevices'] > 0 ? $levels['physicalSecurityDevices_lvl1'] / $levels['physicalSecurityDevices'] : 0);
        $sheet->setCellValue("D{$row}", $levels['physicalSecurityDevices']);
        $sheet->setCellValue("E{$row}", $levels['physicalSecurityDevices'] > 0 ? $levels['physicalSecurityDevices_lvl1'] / $levels['physicalSecurityDevices'] : 0);
        $sheet->setCellValue("F{$row}", $levels['physicalSecurityDevices']);
        $sheet->setCellValue("G{$row}", $levels['physicalSecurityDevices'] > 0 ? $levels['physicalSecurityDevices_lvl1'] / $levels['physicalSecurityDevices'] : 0);
        $row++;

        // WAN
        $sheet->setCellValue("A{$row}", trans('cruds.wan.title'));
        $sheet->setCellValue("B{$row}", $levels['wans']);
        $sheet->setCellValue("C{$row}", $levels['wans'] > 0 ? $levels['wans_lvl1'] / $levels['wans'] : 0);
        $sheet->setCellValue("D{$row}", $levels['wans']);
        $sheet->setCellValue("E{$row}", $levels['wans'] > 0 ? $levels['wans_lvl1'] / $levels['wans'] : 0);
        $sheet->setCellValue("F{$row}", $levels['wans']);
        $sheet->setCellValue("G{$row}", $levels['wans'] > 0 ? $levels['wans_lvl1'] / $levels['wans'] : 0);
        $row++;

        // MAN
        $sheet->setCellValue("A{$row}", trans('cruds.man.title'));
        $sheet->setCellValue("B{$row}", $levels['mans']);
        $sheet->setCellValue("C{$row}", $levels['mans'] > 0 ? $levels['mans_lvl1'] / $levels['mans'] : 0);
        $sheet->setCellValue("D{$row}", $levels['mans']);
        $sheet->setCellValue("E{$row}", $levels['mans'] > 0 ? $levels['mans_lvl1'] / $levels['mans'] : 0);
        $sheet->setCellValue("F{$row}", $levels['mans']);
        $sheet->setCellValue("G{$row}", $levels['mans'] > 0 ? $levels['mans_lvl1'] / $levels['mans'] : 0);
        $row++;

        // LAN
        $sheet->setCellValue("A{$row}", trans('cruds.lan.title'));
        $sheet->setCellValue("B{$row}", $levels['lans']);
        $sheet->setCellValue("C{$row}", $levels['lans'] > 0 ? $levels['lans_lvl1'] / $levels['lans'] : 0);
        $sheet->setCellValue("D{$row}", $levels['lans']);
        $sheet->setCellValue("E{$row}", $levels['lans'] > 0 ? $levels['lans_lvl1'] / $levels['lans'] : 0);
        $sheet->setCellValue("F{$row}", $levels['lans']);
        $sheet->setCellValue("G{$row}", $levels['lans'] > 0 ? $levels['lans_lvl1'] / $levels['lans'] : 0);
        $row++;

        // VLAN
        $sheet->setCellValue("A{$row}", trans('cruds.vlan.title'));
        $sheet->setCellValue("B{$row}", $levels['vlans']);
        $sheet->setCellValue("C{$row}", $levels['vlans'] > 0 ? $levels['vlans_lvl1'] / $levels['vlans'] : 0);
        $sheet->setCellValue("D{$row}", $levels['vlans']);
        $sheet->setCellValue("E{$row}", $levels['vlans'] > 0 ? $levels['vlans_lvl1'] / $levels['vlans'] : 0);
        $sheet->setCellValue("F{$row}", $levels['vlans']);
        $sheet->setCellValue("G{$row}", $levels['vlans'] > 0 ? $levels['vlans_lvl1'] / $levels['vlans'] : 0);

        // =============================================================
        // Save sheet
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        // Return
        return response()->download($path);
    }

    public function changes()
    {
        $path = storage_path('app/changes-' . Carbon::today()->format('Ymd') . '.xlsx');

        /*
        select subject_type, description, YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count
        from audit_logs
        where created_at >= now() - INTERVAL 12 month
        group by subject_type, description, YEAR(created_at), MONTH(created_at);
        */

        $auditLogs = DB::table('audit_logs')
            ->select(DB::raw('subject_type, description, YEAR(created_at), MONTH(created_at), count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->startOfMonth()->addMonth(-12))
            ->groupBy('subject_type', 'description', 'YEAR(created_at)', 'MONTH(created_at)')
            ->get();

        $header = [
            trans('Objet'),
            trans('Action'),
            Carbon::now()->startOfMonth()->addMonth(-12)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-11)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-10)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-9)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-8)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-7)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-6)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-5)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-4)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-3)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-2)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-1)->format('m/Y'),
            Carbon::now()->startOfMonth()->format('m/Y'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');
        // freeze top rows
        $sheet->freezePane('C2');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);
        // white font
        $sheet->getStyle('1')->getFont()->getColor()->setRGB('FFFFFF');
        // background color
        $sheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F77BE');

        // column size and border
        for ($i = 0; $i <= 14; $i++) {
            $col = chr(ord('A') + $i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getStyle("{$col}1")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle("{$col}2")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // Center
        $sheet->getStyle('B1:O141')->getAlignment()->setHorizontal('center');

        // Total months
        $tMonths = Carbon::now()->year * 12 + Carbon::now()->month;

        // App\xxx -> index, title
        $rows = [
            'GDPR' => ['index' => 2, 'title' => trans('cruds.menu.gdpr.title_short')],
            'App\\DataProcessing' => ['index' => 3, 'title' => trans('cruds.dataProcessing.title')],
            'App\\SecurityControl' => ['index' => 6, 'title' => trans('cruds.securityControl.title')],

            'Ecosystem' => ['index' => 9, 'title' => trans('cruds.menu.ecosystem.title_short')],
            'App\\Entity' => ['index' => 10, 'title' => trans('cruds.entity.title')],
            'App\\Relation' => ['index' => 13, 'title' => trans('cruds.relation.title')],

            'Metier' => ['index' => 16, 'title' => trans('cruds.menu.metier.title_short')],
            'App\\MacroProcessus' => ['index' => 17, 'title' => trans('cruds.macroProcessus.title')],
            'App\\Process' => ['index' => 20, 'title' => trans('cruds.process.title')],
            'App\\Activity' => ['index' => 23, 'title' => trans('cruds.activity.title')],
            'App\\Operation' => ['index' => 26, 'title' => trans('cruds.operation.title')],
            'App\\Task' => ['index' => 29, 'title' => trans('cruds.task.title')],
            'App\\Actor' => ['index' => 32, 'title' => trans('cruds.actor.title')],
            'App\\Information' => ['index' => 35, 'title' => trans('cruds.information.title')],

            'Applications' => ['index' => 38, 'title' => trans('cruds.menu.application.title_short')],
            'App\\ApplicationBlock' => ['index' => 39, 'title' => trans('cruds.applicationBlock.title')],
            'App\\MApplication' => ['index' => 42, 'title' => trans('cruds.application.title')],
            'App\\ApplicationService' => ['index' => 45, 'title' => trans('cruds.applicationService.title')],
            'App\\ApplicationModule' => ['index' => 48, 'title' => trans('cruds.applicationModule.title')],
            'App\\Database' => ['index' => 51, 'title' => trans('cruds.database.title')],
            'App\\Flux' => ['index' => 54, 'title' => trans('cruds.flux.title')],

            'Administration' => ['index' => 57, 'title' => trans('cruds.menu.administration.title_short')],
            'App\\ZoneAdmin' => ['index' => 58, 'title' => trans('cruds.zoneAdmin.title')],
            'App\\Annuaire' => ['index' => 61, 'title' => trans('cruds.annuaire.title')],
            'App\\ForestAd' => ['index' => 64, 'title' => trans('cruds.forestAd.title')],
            'App\\DomaineAd' => ['index' => 67, 'title' => trans('cruds.domaineAd.title')],

            'LogicalInfrastructure' => ['index' => 70, 'title' => trans('cruds.menu.logical_infrastructure.title_short')],
            'App\\Network' => ['index' => 71, 'title' => trans('cruds.network.title')],
            'App\\Subnetwork' => ['index' => 74, 'title' => trans('cruds.subnetwork.title')],
            'App\\Gateway' => ['index' => 77, 'title' => trans('cruds.gateway.title')],
            'App\\ExternalConnectedEntity' => ['index' => 80, 'title' => trans('cruds.externalConnectedEntity.title')],
            'App\\NetworkSwitch' => ['index' => 83, 'title' => trans('cruds.networkSwitch.title')],
            'App\\Router' => ['index' => 86, 'title' => trans('cruds.router.title')],
            'App\\SecurityDevice' => ['index' => 89, 'title' => trans('cruds.securityDevice.title')],
            'App\\DhcpServer' => ['index' => 92, 'title' => trans('cruds.dhcpServer.title')],
            'App\\LogicalServer' => ['index' => 95, 'title' => trans('cruds.logicalServer.title')],
            'App\\Certificate' => ['index' => 98, 'title' => trans('cruds.certificate.title')],

            'PhysicalInfrastructure' => ['index' => 101, 'title' => trans('cruds.menu.physical_infrastructure.title_short')],
            'App\\Site' => ['index' => 102, 'title' => trans('cruds.site.title')],
            'App\\Building' => ['index' => 105, 'title' => trans('cruds.building.title')],
            'App\\Bay' => ['index' => 108, 'title' => trans('cruds.bay.title')],
            'App\\PhysicalServer' => ['index' => 111, 'title' => trans('cruds.physicalServer.title')],
            'App\\Workstation' => ['index' => 114, 'title' => trans('cruds.workstation.title')],
            'App\\StorageDevice' => ['index' => 117, 'title' => trans('cruds.storageDevice.title')],
            'App\\Peripheral' => ['index' => 120, 'title' => trans('cruds.peripheral.title')],
            'App\\Phone' => ['index' => 123, 'title' => trans('cruds.phone.title')],
            'App\\PhysicalRouter' => ['index' => 126, 'title' => trans('cruds.physicalRouter.title')],
            'App\\PhysicalSwitch' => ['index' => 129, 'title' => trans('cruds.physicalSwitch.title')],
            'App\\WifiTerminal' => ['index' => 132, 'title' => trans('cruds.wifiTerminal.title')],
            'App\\PhysicalSecurityDevice' => ['index' => 135, 'title' => trans('cruds.physicalSecurityDevice.title')],
            'App\\Wan' => ['index' => 138, 'title' => trans('cruds.wan.title')],
            'App\\Man' => ['index' => 141, 'title' => trans('cruds.man.title')],
            'App\\Lan' => ['index' => 144, 'title' => trans('cruds.lan.title')],
            'App\\Vlan' => ['index' => 147, 'title' => trans('cruds.vlan.title')],
        ];

        // Fill sheet
        $idx = 2;
        foreach ($rows as $key => $row) {
            // $idx = $row['index'];
            $sheet->setCellValue("A{$idx}", $row['title']);
            if (str_starts_with($key, 'App\\')) {
                $sheet->setCellValue("B{$idx}", 'created');
                $sheet->getStyle("B{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF59A14F');
                $sheet->getStyle("A{$idx}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("B{$idx}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("A{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
                $idx++;
                $sheet->setCellValue("B{$idx}", 'updated');
                $sheet->getStyle("B{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF28E2B');
                $sheet->getStyle("A{$idx}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("B{$idx}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("A{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
                $idx++;
                $sheet->setCellValue("B{$idx}", 'deleted');
                $sheet->getStyle("B{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE15759');
                $sheet->getStyle("A{$idx}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("B{$idx}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("A{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
                $idx++;
            } else {
                $sheet->getStyle("A{$idx}")->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle("A{$idx}")->getFont()->setBold(true);
                $sheet->getStyle("A{$idx}:O{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F77BE');
                $idx++;
            }
        }

        // Populate the Timesheet
        foreach ($auditLogs as $auditLog) {
            if (isset($rows[$auditLog->subject_type])) {
                // get row
                $row = $rows[$auditLog->subject_type]['index'];

                // add action index
                if ($auditLog->description === 'updated') {
                    $row += 1;
                } elseif ($auditLog->description === 'deleted') {
                    $row += 2;
                }

                // get year / month
                $year = $auditLog->{'YEAR(created_at)'};
                $month = $auditLog->{'MONTH(created_at)'};

                // compute column
                $delta = 14 - ($tMonths - ($year * 12 + $month));
                $column = chr(ord('A') + $delta);

                // Place value
                $sheet->setCellValue("{$column}{$row}", $auditLog->count);
                $sheet->getStyle("{$column}{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // set the color of the cell
                if ($auditLog->description === 'updated') {
                    $sheet->getStyle("{$column}{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF28E2B');
                } elseif ($auditLog->description === 'deleted') {
                    $sheet->getStyle("{$column}{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE15759');
                } else {
                    $sheet->getStyle("{$column}{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF59A14F');
                }

                // \Log::info("{$column}{$row} ->". $auditLog->subject_type. ', ' . $year . ', ' . $month . ', ' . $auditLog->count);
            }
        }

        // Write speansheet
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        // Return
        return response()->download($path);
    }
}
