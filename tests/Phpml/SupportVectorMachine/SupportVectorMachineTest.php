<?php

declare(strict_types=1);

namespace tests\SupportVectorMachine;

use Phpml\Dataset\ArrayDataset;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\SupportVectorMachine\SupportVectorMachine;
use Phpml\SupportVectorMachine\Type;

class SupportVectorMachineTest extends \PHPUnit_Framework_TestCase
{
    public function testTrainCSVCModelWithLinearKernel()
    {
        $tuples = [
            [ 'a', [1, 3] ],
            [ 'a', [1, 4] ],
            [ 'a', [2, 4] ],
            [ 'b', [3, 1] ],
            [ 'b', [4, 1] ],
            [ 'b', [4, 2] ]
        ];

        $model =
            'svm_type c_svc
kernel_type linear
nr_class 2
total_sv 2
rho 0
label 0 1
nr_sv 1 1
SV
0.25 1:2 2:4 
-0.25 1:4 2:2 
';

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::LINEAR, 100.0);
        $svm->train($tuples);

        $this->assertEquals($model, $svm->getModel());
    }

    public function testPredictSampleWithLinearKernel()
    {
        $tuples = [
            [ 'a', [1, 3] ],
            [ 'a', [1, 4] ],
            [ 'a', [2, 4] ],
            [ 'b', [3, 1] ],
            [ 'b', [4, 1] ],
            [ 'b', [4, 2] ]
        ];

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::LINEAR, 100.0);
        $svm->train($tuples);

        $predictions = $svm->predict([
            [3, 2],
            [2, 3],
            [4, -5],
        ]);

        $this->assertEquals('b', $predictions[0]);
        $this->assertEquals('a', $predictions[1]);
        $this->assertEquals('b', $predictions[2]);
    }

    public function testPredictSampleFromMultipleClassWithRbfKernel()
    {
        $tuples = [
            [ 'a', [1, 3] ],
            [ 'a', [1, 4] ],
            [ 'a', [1, 4] ],
            [ 'b', [3, 1] ],
            [ 'b', [4, 1] ],
            [ 'b', [4, 2] ],
            [ 'c', [-3, -1] ],
            [ 'c', [-4, -1] ],
            [ 'c', [-4, -2] ],
        ];

        $svm = new SupportVectorMachine(Type::C_SVC, Kernel::RBF, 100.0);
        $svm->train($tuples);

        $predictions = $svm->predict([
            [1, 5],
            [4, 3],
            [-4, -3],
        ]);

        $this->assertEquals('a', $predictions[0]);
        $this->assertEquals('b', $predictions[1]);
        $this->assertEquals('c', $predictions[2]);
    }
}
