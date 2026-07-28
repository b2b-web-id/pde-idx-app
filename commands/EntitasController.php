<?php

namespace app\commands;

use app\models\Entitas;
use app\models\Individu;
use app\models\Perusahaan;
use yii\console\Controller;

/**
 * Reconciles canonical entities with individual and company master data.
 */
class EntitasController extends Controller
{
    public function actionSync()
    {
        $count = 0;

        foreach (Individu::find()->each() as $individu) {
            if (Entitas::syncFromIndividu($individu) !== null) {
                $count++;
            }
        }

        foreach (Perusahaan::find()->each() as $perusahaan) {
            if (Entitas::syncFromPerusahaan($perusahaan) !== null) {
                $count++;
            }
        }

        $this->stdout("Synchronized {$count} canonical entities.\n");
    }
}
