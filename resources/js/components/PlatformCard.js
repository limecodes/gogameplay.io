import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { validatePlatform } from '../actions/validation';

class PlatformCard extends Component {

	handleConfimPlatform() {
		this.props.validatePlatform();
	}

	render() {
		let androidStyle = { padding: '1rem' };
		let iosStyle = { padding: '1rem' };

		if (this.props.device == 'android') {
			androidStyle = {
				...androidStyle,
				border: '1px solid #a9d301',
				borderRadius: '2.25rem'
			}
		} else if (this.props.device == 'ios') {
			iosStyle = {
				...iosStyle,
				border: '1px solid black',
				borderRadius: '2.25rem'
			}
		}

		return (
			<div className="card">
				<div className="card-header">Platform</div>
					<div className="card-body">
						<div className="row">
			                <div className="col-6" style={ androidStyle }>
			                	<img src="http://static.offers.gogameplay.io/images/android.png" style={{ width: '50%' }} />
			                </div>

			                <div className="col-6" style={ iosStyle }>
			                	<img src="http://static.offers.gogameplay.io/images/ios.png" style={{ width: '50%' }} />
			                </div>
		                </div>
		            </div>
		            <div className="card-footer" style={{ textAlign: 'right' }}>
		            	<button className="btn btn-success" onClick={ this.handleConfimPlatform.bind(this) }>Confirm</button>
		            </div>
		    </div>
	    );
	}

}

export default connect(null, { validatePlatform })(PlatformCard);