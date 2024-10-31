<?php
/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Api
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Options;
use Twilio\Values;

abstract class QueueOptions
{
    /**
     * @param int $maxSize The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     * @return CreateQueueOptions Options builder
     */
    public static function create(
        
        int $maxSize = Values::INT_NONE

    ): CreateQueueOptions
    {
        return new CreateQueueOptions(
            $maxSize
        );
    }




    /**
     * @param string $friendlyName A descriptive string that you created to describe this resource. It can be up to 64 characters long.
     * @param int $maxSize The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     * @return UpdateQueueOptions Options builder
     */
    public static function update(
        
        string $friendlyName = Values::NONE,
        int $maxSize = Values::INT_NONE

    ): UpdateQueueOptions
    {
        return new UpdateQueueOptions(
            $friendlyName,
            $maxSize
        );
    }

}

class CreateQueueOptions extends Options
    {
    /**
     * @param int $maxSize The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     */
    public function __construct(
        
        int $maxSize = Values::INT_NONE

    ) {
        $this->options['maxSize'] = $maxSize;
    }

    /**
     * The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     *
     * @param int $maxSize The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     * @return $this Fluent Builder
     */
    public function setMaxSize(int $maxSize): self
    {
        $this->options['maxSize'] = $maxSize;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Api.V2010.CreateQueueOptions ' . $options . ']';
    }
}




class UpdateQueueOptions extends Options
    {
    /**
     * @param string $friendlyName A descriptive string that you created to describe this resource. It can be up to 64 characters long.
     * @param int $maxSize The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     */
    public function __construct(
        
        string $friendlyName = Values::NONE,
        int $maxSize = Values::INT_NONE

    ) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['maxSize'] = $maxSize;
    }

    /**
     * A descriptive string that you created to describe this resource. It can be up to 64 characters long.
     *
     * @param string $friendlyName A descriptive string that you created to describe this resource. It can be up to 64 characters long.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName): self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     *
     * @param int $maxSize The maximum number of calls allowed to be in the queue. The default is 100. The maximum is 5000.
     * @return $this Fluent Builder
     */
    public function setMaxSize(int $maxSize): self
    {
        $this->options['maxSize'] = $maxSize;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Api.V2010.UpdateQueueOptions ' . $options . ']';
    }
}

