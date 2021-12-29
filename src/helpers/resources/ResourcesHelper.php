<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

/** A helper class for resource-related API endpoints. */
class ResourcesHelper {
    /** @var APIWrapper The current wrapper instance in use. */
    private $wrapper;

    /**
	 * Construct a new alerts helper from a wrapper instance.
	 *
	 * @param APIWrapper The current wrapper instance in use.
	 */
    function __construct(APIWrapper $wrapper) {
        $this->wrapper = $wrapper;
    }

    /**
	 * List a single page of resources.
	 *
	 * @return APIResponse The parsed API response.
	 */
    function list(): APIResponse {
        return $this->wrapper->get("resources");
    }

    /**
	 * List a single page of resources you own.
	 *
	 * @return APIResponse The parsed API response.
	 */
    function listOwned(): APIResponse {
        return $this->wrapper->get("resources/owned");
    }

    /**
	 * List a single page of resources you collaborate on.
	 *
	 * @return APIResponse The parsed API response.
	 */
    function listCollaborated(): APIResponse {
        return $this->wrapper->get("resources/collaborated");
    }

    /**
	 * Fetch information about a resource.
	 *
     * @param int The identifier of the resource.
	 * @return APIResponse The parsed API response.
	 */
    function fetch(int $resource_id): APIResponse {
        return $this->wrapper->get("resources/" . $resource_id);
    }
}