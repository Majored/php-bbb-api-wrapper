<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

/** A helper class for license-related API endpoints. */
class LicensesHelper {
    /** @var APIWrapper The current wrapper instance in use. */
    private $wrapper;

    /**
	 * Construct a new licenses helper from a wrapper instance.
	 *
	 * @param APIWrapper The current wrapper instance in use.
	 */
    function __construct(APIWrapper $wrapper) {
        $this->wrapper = $wrapper;
    }

    /**
	 * List a single page of resource licenses.
	 *
     * @param int The identifier of the resource.
     * @param array An optional associated array of sort options.
     * 
	 * @return APIResponse The parsed API response.
	 */
    function list(int $resource_id, array $sort = []): APIResponse {
        return $this->wrapper->get(sprintf("resources/%d/licenses", $resource_id), $sort);
    }

    /**
	 * Fetch a resource license.
	 *
     * @param int The identifier of the resource.
     * @param int The identifier of the license.
     * 
	 * @return APIResponse The parsed API response.
	 */
    function fetch(int $resource_id, int $license_id): APIResponse {
        return $this->wrapper->get(sprintf("resources/%d/licenses/%d", $resource_id, $license_id));
    }

    /**
	 * Fetch a resource license by member.
	 *
     * @param int The identifier of the resource.
     * @param int The identifier of the member.
     * 
	 * @return APIResponse The parsed API response.
	 */
    function fetchByMember(int $resource_id, int $member_id): APIResponse {
        // TODO: Add query parameters for nonce/timestamp.
        return $this->wrapper->get(sprintf("resources/%d/licenses/member/%d", $resource_id, $member_id));
    }

    /**
	 * Issue a new resource license.
	 *
     * @param int The identifier of the resource.
     * @param int The identifier of the member.
     * @param int The start date of the license as a UNIX timestamp.
     * @param int The end date of the license as a UNIX timestamp.
     * @param bool Whether or not the license should be active.
     * 
	 * @return APIResponse The parsed API response.
	 */
    function issue(int $resource_id, int $member_id, int $start_date, int $end_date, bool $active): APIResponse {
        $body = [
            "purchaser_id" => $member_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "active" => $active
        ];

        return $this->wrapper->post(sprintf("resources/%d/licenses", $resource_id), $body);
    }

    /**
	 * Modify an existing resource license.
	 *
     * @param int The identifier of the resource.
     * @param int The identifier of the license.
     * @param int The start date of the license as a UNIX timestamp, or null if unchanged.
     * @param int The end date of the license as a UNIX timestamp, or null if unchanged.
     * @param bool Whether or not the license should be active, or null if unchanged.
     * 
	 * @return APIResponse The parsed API response.
	 */
    function modify(int $resource_id, int $license_id, int $start_date, int $end_date, bool $active): APIResponse {
        $body = [
            "start_date" => $start_date,
            "end_date" => $end_date,
            "active" => $active
        ];

        return $this->wrapper->patch(sprintf("resources/%d/licenses/%d", $resource_id, $license_id), $body);
    }
}