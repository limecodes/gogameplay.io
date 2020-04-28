import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

class Instructions extends Component {

	render() {
		const DivCheck = () => { 
			return (
				<div className="badge badge-success">Check</div>
			) 
		};
		return (
			<div className="col-12">
				<small>
					<ul>
						{(this.props.validation.platform) ? <li><DivCheck /><span>{' '}1. Confirmed your device platform</span></li> : <li><p>{' '}</p></li>}
						{(this.props.validation.carrier) ? <li><DivCheck /><span>{' '}2. Verified your carrier</span></li> : <li><p>{' '}</p></li>}
					</ul>
				</small>
			</div>
		);
	}
}

const mapStateToProps = state => ({
	validation: state.validation
});

export default connect(mapStateToProps, {})(Instructions);