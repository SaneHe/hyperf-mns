<?php
declare(strict_types=1);

namespace Sane;

use Hyperf\Contract\PackerInterface;

class MnsMessagePacker implements PackerInterface
{

    /**
     * @param $data
     *
     * @return string
     */
    public function pack($data): string
    {
        return json_encode($data);
    }

    /**
     * @param string $data
     *
     * @return mixed
     */
    public function unpack(string $data)
    {
        if (!$data = json_decode($data, true)) {
            return $data;
        }

        if (empty($data['job']) || (empty($data['queue']) && empty($data['topic']))) {
            return '';
        }

        $jobClass = $data['job'];

        return new $jobClass($data['data'] ?? null, $data['queue'] ?? null, $data['topic'] ?? null);
    }
}
