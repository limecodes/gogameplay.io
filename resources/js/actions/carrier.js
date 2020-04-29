import { RECIEVED_CARRIER_LIST_START, RECEIVED_CARRIER_LIST_SUCCESS, RECEIVED_CARRIER_LIST_FAIL } from './types';

import axios from 'axios';

export const getCarrierList = (uid) => async dispatch => {
	dispatch({
		type: RECIEVED_CARRIER_LIST_START
	});

	try {
		const response = await axios.post('/api/carrierlist', {
			'uid': uid
		});

		const payload = response.data;

		dispatch({
			type: RECEIVED_CARRIER_LIST_SUCCESS,
			payload: payload
		});
	} catch (error) {
		dispatch({
			type: RECEIVED_CARRIER_LIST_FAIL
		});
	}
}